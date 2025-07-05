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
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();
            $table->string("event_schedule_code", 36)->unique()->nullable(false);
            $table->string("title", 255)->unique()->nullable(false);
            $table->text("description")->nullable(true);
            $table->date("event_date")->nullable(false);
            $table->time("start_time")->nullable(false);
            $table->time("end_time")->nullable(false);
            $table->string("location", 255)->nullable(false);
            $table->string("speaker")->nullable(true);
            $table->string("banner", 255)->nullable(true);
            $table->enum("status", ["drafted", "scheduled", "finished", "cancelled", "archived"])->default("drafted");
            $table->foreignIdFor(Facility::class)->nullable(true);
            $table->timestamps();

            $table->foreign("facility_id")->references("id")->on("facilities");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};
