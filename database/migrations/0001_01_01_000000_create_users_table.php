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
            $table->string('nik')->primary(); // ganti ID dengan NIK sebagai primary key
            $table->string('nama');
            $table->string('password');
            $table->enum('role', ['operator', 'supervisor']);
            $table->timestamps();
        });

        // Optional: Hapus ini jika tidak pakai fitur auth default Laravel
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
