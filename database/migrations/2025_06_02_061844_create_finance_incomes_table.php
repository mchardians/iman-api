<?php

use App\Models\FinanceCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('finance_incomes', function (Blueprint $table) {
            $table->id();
            $table->string('income_transaction', 36)->unique();
            $table->date('date')->nullable(false);
            $table->foreignIdFor(FinanceCategory::class);
            $table->text('description')->nullable(false);
            $table->integer('amount')->nullable(false);
            $table->string('transaction_receipt')->nullable(true);
            $table->timestamps();

            $table->foreign('finance_category_id')->references('id')->on('finance_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_incomes');
    }
};
