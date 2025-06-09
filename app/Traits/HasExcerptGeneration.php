<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasExcerptGeneration {
    public static function bootHasExcerptGeneration() {
        static::creating(function ($model) {
            if(empty($model->excerpt) && !empty($model->content)) {
                $model->excerpt = Str::limit(strip_tags($model->content), 150, '...');
            }
        });
        static::updating(function ($model) {
            $originalContent = $model->getOriginal('content');
            if ($originalContent !== $model->content) {
                $model->excerpt = Str::limit(strip_tags($model->content), 150, '...');
            }
        });
    }
}