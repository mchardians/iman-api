<?php

namespace Database\Factories;

use App\Models\InfaqType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InfaqType>
 */
class InfaqTypeFactory extends Factory
{
    protected $model = InfaqType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->creditCardType(),
            "description" => $this->faker->text(100)
        ];
    }
}
