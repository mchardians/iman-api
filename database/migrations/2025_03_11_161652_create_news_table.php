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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class);
            $table->string('news_code', 36)->unique();
            $table->string('title')->nullable(false);
            $table->string('slug')->unique()->nullable(false);
            $table->string('thumbnail')->nullable(true);
            $table->longText('content');
            $table->string('excerpt', 255)->nullable(false);
            $table->enum('status', ['drafted', 'published', 'archived'])->default('drafted');
            $table->timestamp('published_at')->nullable(true);
            $table->timestamp('archived_at')->nullable(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
