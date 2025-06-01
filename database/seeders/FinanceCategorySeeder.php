<?php

namespace Database\Seeders;

use App\Models\FinanceCategory;
use Database\Factories\FinanceCategoryFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FinanceCategory::factory(20)->create();
    }
}
