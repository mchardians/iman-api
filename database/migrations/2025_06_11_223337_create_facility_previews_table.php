<?php

use App\Models\Facility;
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
        Schema::create('facility_previews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Facility::class);
            $table->string('image_path');
            $table->timestamps();

            $table->foreign('facility_id')->references('id')->on('facilities')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_previews');
    }
};
