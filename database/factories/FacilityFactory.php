<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Facility>
 */
class FacilityFactory extends Factory
{
    protected $model = Facility::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $facilities = [
            "Ruang Penitipan Barang", "Serambi Masjid", "Dummy 1", "Dummy 2",
            "Dummy 3", "Dummy 4", "Ruang Imam & Khotib", "Perpustakaan Masjid (Maktabah)",
            "Ruang Kajian & Belajar", "Ruang Multimedia & Dakwah", "Aula Serbaguna",
            "Ruang Majelis & Pertemuan", "Kantor Sekretariat DKM", "Ruang Konsultasi Syariah",
            "Area Bermain Anak", "Dapur Masjid", "Ruang Penerimaan Tamu",
            "Area Parkir", "Ruang Inventaris & Logistik", "Ruang Penyimpanan"
        ];

        return [
            "name" => $this->faker->unique()->randomElement($facilities),
            "description" => $this->faker->sentence(10),
            "capacity" => $this->faker->numberBetween(10, 200),
            "price_per_hour" => $this->faker->numberBetween(50000, 300000),
            "status" => $this->faker->randomElement(["reservable", "unreservable", "reserved"]),
            "cover_image" => Storage::url($this->fillCoverImage())
        ];
    }

    private function fillCoverImage() {
        $filename = 'facility-covers/' . Str::uuid() . '.jpg';

        $imageContent = Http::get('https://picsum.photos/640/480')->body();
        Storage::disk('public')->put($filename, $imageContent);

        return $filename;
    }

    public function configure():static {
        return $this->afterCreating(function(Facility $facility) {
            for ($i = 1; $i <= rand(3, 5); $i++) {
                $fileName = "facility-previews/". Str::uuid() . ".jpg";
                $imageContent = Http::get('https://picsum.photos/640/480')->body();

                Storage::disk('public')->put($fileName, $imageContent);

                $facility->facilityPreview()->create([
                    'image_path' => Storage::url($fileName),
                ]);
            }
        });
    }
}
