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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('facility_code', 36)->unique();
            $table->string('name')->nullable(false);
            $table->text('description')->nullable(false);
            $table->integer('capacity')->nullable(false)->default(0);
            $table->integer('price_per_hour')->nullable(false)->default(0);
            $table->enum('status', ['available', 'maintenance', 'unavailable'])->default('available');
            $table->string('cover_image')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
