<?php

namespace Database\Seeders;

use App\Libraries\CodeGeneration;
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
                    "role_code" => (new CodeGeneration(Role::class, "role_code", "ROL"))->getGeneratedResourceCode(),
                    "name" => $role
                ]);

            }
            continue;

        }

    }
}
