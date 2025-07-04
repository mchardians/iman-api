<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivitySchedule>
 */
class ActivityScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $facility = Facility::inRandomOrder()->first();
        $useExistingFacility = $this->faker->boolean(70);

        $location = $useExistingFacility && $facility ? $facility->name : "Aula ". $this->faker->company();
        $facilityId = $useExistingFacility && $facility ? $facility->id : null;

        return [
            "title" => $this->faker->words(3, true),
            "description" => $this->faker->sentence(),
            "day_of_week" => $this->faker->randomElement([
                "senin", "selasa", "rabu", "kamis", "jumat", "sabtu", "minggu"
            ]),
            "start_time" => $this->faker->time("H:i"),
            "end_time" => $this->faker->time("H:i"),
            "location" => $location,
            "repeat_type" => $this->faker->randomElement(["daily", "weekly", "monthly"]),
            "status" => $this->faker->randomElement(["active", "inactive", "cancelled", "done"]),
            "facility_id" => $facilityId
        ];
    }
}
