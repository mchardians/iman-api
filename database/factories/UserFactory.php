<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model"s default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "email" => fake()->unique()->safeEmail(),
            "password" => static::$password ??= Hash::make("password"),
            "photo" => Storage::url($this->fillPhotoProfiles()),
            "role_id" => Role::inRandomOrder()->value('id'),
        ];
    }

    private function fillPhotoProfiles() {
        $filename = 'photo-profiles/' . Str::uuid() . '.jpg';

        $imageContent = Http::get('https://picsum.photos/400/400')->body();
        Storage::disk('public')->put($filename, $imageContent);

        return $filename;
    }

    /**
     * Indicate that the model"s email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            "email_verified_at" => null,
        ]);
    }
}
