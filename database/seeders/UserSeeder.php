<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "name" => "Administrator",
                "email" => "administrator@gmail.com",
                "password" => bcrypt("administrator"),
                "role_id" => 1
            ],
            [
                "name" => "Ketua Takmir",
                "email" => "ketuatakmir@gmail.com",
                "password" => bcrypt("ketuatakmir"),
                "role_id" => 2
            ],
            [
                "name" => "Sekretaris",
                "email" => "sekretaris@gmail.com",
                "password" => bcrypt("sekretaris"),
                "role_id" => 3
            ],
            [
                "name" => "Bendahara",
                "email" => "bendahara@gmail.com",
                "password" => bcrypt("bendahara"),
                "role_id" => 4
            ],
            [
                "name" => "Donatur",
                "email" => "donatur@gmail.com",
                "password" => bcrypt("donaturtest"),
                "role_id" => 5
            ],
            [
                "name" => "Jamaah Umum",
                "email" => "jamaahumum@gmail.com",
                "password" => bcrypt("jamaahumum"),
                "role_id" => 6
            ],
        ];

        foreach ($users as $user) {
            if(!User::where("email", $user["email"])->exists()) {
                User::create([
                    "name" => $user["name"],
                    "email" => $user["email"],
                    "password" => $user["password"],
                    "role_id" => $user["role_id"]
                ]);
            }
            continue;
        }

        User::factory(14)->create();
    }
}
