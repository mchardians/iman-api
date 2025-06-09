<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::factory(10)->published()->create()->each(function($news) {
            $this->attachRandomCategories($news);
        });

        News::factory(5)->archived()->create()->each(function($news) {
            $this->attachRandomCategories($news);
        });

        News::factory(5)->create()->each(function($news) {
            $this->attachRandomCategories($news);
        });
    }

    private function attachRandomCategories(News $news) {
        $newsCategoryId = NewsCategory::inRandomOrder()->take(rand(1,3))->pluck("id");
        $news->newsCategory()->sync($newsCategoryId);
    }
}
