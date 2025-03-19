<?php

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
        Schema::create('income_infaq_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\InfaqType::class);
            $table->string('transaction_code', 36)->unique();
            $table->string('name');
            $table->integer('amount');
            $table->timestamps();

            $table->foreign('infaq_type_id')->references('id')->on('infaq_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_infaq_transactions');
    }
};
