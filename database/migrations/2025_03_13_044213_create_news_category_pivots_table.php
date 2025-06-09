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
        Schema::create('news_category_pivots', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\News::class);
            $table->foreignIdFor(\App\Models\NewsCategory::class);

            $table->primary(['news_id', 'news_category_id']);

            $table->foreign('news_id')->references('id')
            ->on('news')
            ->onDelete('cascade');
            $table->foreign('news_category_id')->references('id')
            ->on('news_categories')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_category_pivots');
    }
};
