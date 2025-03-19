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
        Schema::create('facility_reservation_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\FacilityReservation::class);
            $table->string('facility_payment_code', 36)->unique();
            $table->integer('amount');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->date('payment_date');
            $table->timestamps();

            $table->foreign('facility_reservation_id')->references('id')->on('facility_reservations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_reservation_payments');
    }
};
