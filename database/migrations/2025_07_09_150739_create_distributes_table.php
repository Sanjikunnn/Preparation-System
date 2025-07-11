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
        Schema::create('distributes', function (Blueprint $table) {
            $table->string('id')->primary(); // ubah dari $table->id()
            $table->string('id_hasil_upper');
            $table->timestamp('waktu_proses');
            $table->string('no_barcode');
            $table->timestamps();

            $table->foreign('id_hasil_upper')->references('id')->on('hasil_uppers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributes');
    }
};
