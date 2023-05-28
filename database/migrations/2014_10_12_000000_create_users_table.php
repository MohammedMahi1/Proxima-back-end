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
            $table->string('name');
            $table->string('type');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean("verify")->default(false);
            $table->string('password');
            $table->string('image_profile')->nullable();
            $table->string('image_url')->nullable();
            $table->string('cover_profile')->nullable();
            $table->string('cover_url')->nullable();
            $table->timestamps();
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
