<?php

namespace Database\Factories;

use App\Models\NewsCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsCategory>
 */
class NewsCategoryFactory extends Factory
{
    protected $model = NewsCategory::class;
    /**
     * Define the model"s default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            "Politik", "Hukum", "Ekonomi", "Bisnis", "Olahraga", "Teknologi",
            "Sains", "Otomotif", "Hiburan", "Gaya Hidup", "Kesehatan",
            "Pendidikan", "Internasional", "Daerah", "Opini", "Kuliner",
            "Wisata", "Budaya", "Properti", "Cek Fakta"
        ];
        return [
            "name" => $this->faker->randomElement($categories)
        ];
    }
}
