<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Str;

trait HasExcerptGeneration
{
    public static function bootHasExcerptGeneration()
    {
        static::creating(function ($model) {
            if (empty($model->excerpt) && !empty($model->content)) {
                $model->excerpt = $model->generateAdvancedExcerpt($model->content);
            }
        });

        static::updating(function ($model) {
            $originalContent = $model->getOriginal('content');
            $originalExcerpt = $model->getOriginal('excerpt');

            if ($originalContent !== $model->content) {
                if (empty($model->excerpt) ||
                    $originalExcerpt === $model->generateAdvancedExcerpt($originalContent)) {
                    $model->excerpt = $model->generateAdvancedExcerpt($model->content);
                }
            }
        });
    }

    /**
     * Generate excerpt from content
     *
     * @param string $content
     * @param int $limit
     * @return string
     */
    protected function generateExcerpt($content, $limit = 250)
    {
        return $this->generateAdvancedExcerpt($content, $limit);
    }

    /**
     * Generate excerpt with more advanced cleaning
     * Supports various editor formats including Lexical JSON
     *
     * @param string $content
     * @param int $limit
     * @return string
     */
    protected function generateAdvancedExcerpt($content, $limit = 250)
    {
        if (empty($content)) {
            return '';
        }

        $cleanContent = $this->extractTextFromContent($content);

        if (empty($cleanContent)) {
            return $this->getFallbackExcerpt();
        }

        $cleanContent = $this->cleanExtractedText($cleanContent);

        $excerpt = Str::limit($cleanContent, $limit, '...');

        return $excerpt;
    }

    /**
     * Extract text from various content formats
     *
     * @param string $content
     * @return string
     */
    protected function extractTextFromContent($content)
    {
        if ($this->isJsonContent($content)) {
            return $this->extractTextFromJson($content);
        }

        if ($this->isHtmlContent($content)) {
            return $this->extractTextFromHtml($content);
        }

        return $content;
    }

    /**
     * Check if content is JSON format (Lexical, etc.)
     *
     * @param string $content
     * @return bool
     */
    protected function isJsonContent($content)
    {
        if (!is_string($content)) return false;

        $content = trim($content);
        return (
            (str_starts_with($content, '{') && str_ends_with($content, '}')) ||
            (str_starts_with($content, '[') && str_ends_with($content, ']'))
        ) && json_decode($content) !== null;
    }

    /**
     * Check if content is HTML format
     *
     * @param string $content
     * @return bool
     */
    protected function isHtmlContent($content)
    {
        return $content !== strip_tags($content);
    }

    /**
     * Extract text from JSON content (Lexical format)
     *
     * @param string $jsonContent
     * @return string
     */
    protected function extractTextFromJson($jsonContent)
    {
        try {
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return '';
            }

            return $this->extractTextFromJsonNodes($data);

        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Recursively extract text from JSON nodes
     *
     * @param array|mixed $data
     * @return string
     */
    protected function extractTextFromJsonNodes($data)
    {
        $text = '';

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if ($key === 'text' && is_string($value)) {
                    $text .= $value . ' ';
                } elseif ($key === 'children' && is_array($value)) {
                    $text .= $this->extractTextFromJsonNodes($value);
                } elseif (is_array($value)) {
                    $text .= $this->extractTextFromJsonNodes($value);
                }
            }
        }

        return $text;
    }

    /**
     * Extract text from HTML content
     *
     * @param string $htmlContent
     * @return string
     */
    protected function extractTextFromHtml($htmlContent)
    {
        $htmlContent = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $htmlContent);
        $htmlContent = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/mi', '', $htmlContent);

        $htmlContent = preg_replace('/<!--.*?-->/s', '', $htmlContent);

        $htmlContent = $this->replaceMediaElementsWithText($htmlContent);

        $cleanContent = strip_tags($htmlContent);

        $cleanContent = html_entity_decode($cleanContent, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $cleanContent;
    }

    /**
     * Replace media elements with descriptive text
     *
     * @param string $html
     * @return string
     */
    protected function replaceMediaElementsWithText($html)
    {
        $html = preg_replace_callback(
            '/<img[^>]*alt=["\']([^"\']*)["\'][^>]*>/i',
            function($matches) {
                return !empty($matches[1]) ? $matches[1] . ' ' : '[Gambar] ';
            },
            $html
        );

        $html = preg_replace('/<img[^>]*>/i', '[Gambar] ', $html);

        $html = preg_replace('/<video[^>]*>.*?<\/video>/is', '[Video] ', $html);

        $html = preg_replace('/<audio[^>]*>.*?<\/audio>/is', '[Audio] ', $html);

        $html = preg_replace('/<iframe[^>]*>.*?<\/iframe>/is', '[Konten Tertanam] ', $html);

        $html = preg_replace_callback(
            '/<figure[^>]*>.*?<figcaption[^>]*>(.*?)<\/figcaption>.*?<\/figure>/is',
            function($matches) {
                $caption = strip_tags($matches[1]);
                return !empty($caption) ? $caption . ' ' : '[Media] ';
            },
            $html
        );

        $html = preg_replace('/<figure[^>]*>.*?<\/figure>/is', '[Media] ', $html);

        return $html;
    }

    /**
     * Clean extracted text
     *
     * @param string $text
     * @return string
     */
    protected function cleanExtractedText($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        $text = trim($text);

        return $text;
    }

    /**
     * Get fallback excerpt when content is empty after cleaning
     *
     * @return string
     */
    protected function getFallbackExcerpt()
    {
        return 'Konten berisi media atau elemen visual';
    }

    /**
     * Manually set excerpt
     *
     * @param string $excerpt
     * @return $this
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    /**
     * Get excerpt with fallback to auto-generated
     *
     * @return string
     */
    public function getExcerptAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        if (!empty($this->content)) {
            return $this->generateAdvancedExcerpt($this->content);
        }

        return '';
    }
}