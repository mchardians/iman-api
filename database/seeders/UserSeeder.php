<?php

namespace Database\Seeders;

use App\Libraries\CodeGeneration;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            "user_code" => (new CodeGeneration(User::class, "user_code", "USR"))->getGeneratedCode(),
            "name" => "Mochammad Ardiansyah",
            "email" => "mchardians@gmail.com",
            "password" => bcrypt("mchardians"),
            "role_id" => 1
        ]);
    }
}
