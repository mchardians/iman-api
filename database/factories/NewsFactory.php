<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => User::inRandomOrder()->value("id"),
            "title" => $this->faker->sentence(),
            "thumbnail" => Storage::url($this->fillThumbnailPlaceholder()),
            "content" => "<p>" . implode("</p><p>", $this->faker->paragraphs(5)) . "</p>",
            "status" => "drafted",
            "published_at" => null,
            "archived_at" => null,
        ];
    }

    private function fillThumbnailPlaceholder() {
        $filename = 'thumbnails/' . Str::uuid() . '.jpg';

        $imageContent = Http::get('https://picsum.photos/640/480')->body();
        Storage::disk('public')->put($filename, $imageContent);

        return $filename;
    }

    public function published() {
        return $this->state(function() {
            return [
                "status" => "published",
                "published_at" => now()
            ];
        });
    }

    public function archived() {
        return $this->state(function() {
            return [
                "status" => "archived",
                "archived_at" => now()
            ];
        });
    }
}
