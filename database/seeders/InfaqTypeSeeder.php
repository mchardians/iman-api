<?php

namespace Database\Seeders;

use App\Models\InfaqType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfaqTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InfaqType::factory(20)->create();
    }
}
