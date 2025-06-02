<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\FinanceCategory;
use App\Models\FinanceExpense;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinanceExpense>
 */
class FinanceExpenseFactory extends Factory
{
    protected $model = FinanceExpense::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPdf = $this->faker->boolean;
        $directory = 'public/expense-receipts';
        Storage::makeDirectory($directory);

        if ($isPdf) {
            $fileName = Str::uuid() . '.pdf';
            $filePath = $directory . '/' . $fileName;
            Storage::put($filePath, $this->faker->text(200));
        } else {
            $fileName = Str::uuid() . '.jpg';
            $fullImagePath = storage_path("app/{$directory}/{$fileName}");

            $this->faker->image(
                dirname($fullImagePath),
                640,
                480,
                null,
                false,
                true,
                $fileName
            );

            $filePath = $directory . '/' . $fileName;
        }

        return [
            "date" => $this->faker->date(),
            "finance_category_id" => FinanceCategory::inRandomOrder()->value('id'),
            "description" => $this->faker->sentence(10),
            "amount" => $this->faker->numberBetween(1000, 1000000),
            "transaction_receipt" => 'storage/expense-receipts/' . basename($filePath)
        ];
    }
}
