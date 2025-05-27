<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ["Administrator", "Ketua Takmir", "Sekretaris", "Bendahara", "Donatur", "Jamaah Umum"];

        foreach ($roles as $role) {
            if(!Role::where('name', $role)->exists()) {
                Role::create([
                    "name" => $role
                ]);
            }

            continue;
        }

        Role::factory(14)->create();
    }
}
