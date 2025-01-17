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
        Schema::create('user_auth', function (Blueprint $table) {
            $table->id();
            $table->string('name', 500);
            $table->string('email_or_phone', 5000);
            $table->string('password', 1000);
            $table->string('user_role', 15)->default('USER');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_auth');
    }
};
