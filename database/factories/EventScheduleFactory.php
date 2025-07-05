<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventSchedule>
 */
class EventScheduleFactory extends Factory
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

        $dateInAMonth = $this->faker->dateTimeBetween("+1 days", "+1 month");
        $endTimeFromDate = (clone $dateInAMonth)->modify("+2 hours");

        return [
            "title" => $this->faker->sentence(3),
            "description" => $this->faker->paragraph,
            "event_date" => $dateInAMonth->format("Y-m-d"),
            "start_time" => $dateInAMonth->format("H:i:s"),
            "end_time" => $endTimeFromDate->format("H:i:s"),
            "location" => $location,
            "speaker" => $this->faker->name,
            "banner" => Storage::url($this->fillBannerPlaceholder()),
            "status" => $this->faker->randomElement(["drafted", "scheduled", "finished", "cancelled", "archived"]),
            "facility_id" => $facilityId,
        ];
    }

    private function fillBannerPlaceholder() {
        $filename = 'event-banners/' . Str::uuid() . '.jpg';

        $imageContent = Http::get('https://picsum.photos/640/480')->body();
        Storage::disk('public')->put($filename, $imageContent);

        return $filename;
    }
}
