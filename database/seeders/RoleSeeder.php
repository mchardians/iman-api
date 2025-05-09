<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Helpers\CodeGeneration;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Role::create([
            "role_code" => (new CodeGeneration(Role::class, "role_code", "ROL"))->getGeneratedCode(),
            "name" => "Admin"
        ]);
    }
}
