<?php

namespace Database\Seeders;

use App\Models\ActivitySchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivitySchedule::factory(20)->create();
    }
}
