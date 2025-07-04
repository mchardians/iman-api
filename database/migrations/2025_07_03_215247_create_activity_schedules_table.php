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
        Schema::create('activity_schedules', function (Blueprint $table) {
            $table->id();
            $table->string("activity_code", 36)->unique();
            $table->string('title', 255)->nullable(false);
            $table->text('description')->nullable(false);
            $table->string("day_of_week", 6)->nullable(false);
            $table->time("start_time")->nullable(false);
            $table->time("end_time")->nullable(false);
            $table->string("location", 255)->nullable(false);
            $table->enum("repeat_type", ["daily", "weekly", "monthly"])->nullable(false);
            $table->enum("status", ["active", "inactive", "cancelled", "done"])
            ->default("inactive")->nullable(false);
            $table->foreignIdFor(Facility::class)->nullable(true);
            $table->timestamps();

            $table->foreign('facility_id')->references('id')->on('facilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_schedules');
    }
};
