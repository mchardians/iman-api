<?php

namespace Database\Factories;

use Exception;
use Illuminate\Support\Str;
use App\Models\FinanceExpense;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\Http;
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
        $mimeType = $this->faker->randomElement(["image", "pdf"]);

        return [
            "date" => $this->faker->date(),
            "finance_category_id" => FinanceCategory::inRandomOrder()->where("type", "=", "expense")->value('id'),
            "description" => $this->faker->sentence(10),
            "amount" => $this->faker->numberBetween(1000, 1000000),
            "transaction_receipt" => Storage::url($this->fillTransactionReceipt($mimeType))
        ];
    }

    public function fillTransactionReceipt(string $mimeType = "image") {
        if(!empty($mimeType) && in_array($mimeType, ["image", "pdf"])) {
            switch($mimeType) {
                case "image":
                    $filename = "expense-receipts/img/" . Str::uuid() . ".jpg";

                    $imageContent = Http::get("https://picsum.photos/640/480")->body();
                    Storage::disk("public")->put($filename, $imageContent);

                    return $filename;
                case "pdf":
                    $fileName = "expense-receipts/pdf/" . Str::uuid() . ".pdf";

                    Storage::disk("public")->put($fileName, $this->faker->sentence());

                    return $fileName;
            }
        }

        throw new Exception("An error occured while generating transaction receipt! Please ensure your mimetype is neither empty nor invalid mimetype");
    }
}
