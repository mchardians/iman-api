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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Role::class);
            $table->string('user_code', 36)->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->string('phone', 15);
            $table->string('password');

            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
