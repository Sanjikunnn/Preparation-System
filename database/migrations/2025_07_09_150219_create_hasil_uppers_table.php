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
        Schema::create('hasil_uppers', function (Blueprint $table) {
            $table->string('id')->primary(); // ubah dari $table->id()
            $table->string('id_komponen_1');
            $table->string('id_komponen_2');
            $table->string('id_komponen_3');
            $table->string('id_produk_jadi');
            $table->timestamp('waktu_proses');
            $table->string('no_barcode');
            $table->integer('total_proses');
            $table->timestamps();

            $table->foreign('id_komponen_1')->references('id')->on('komponen_produk_jadis');
            $table->foreign('id_komponen_2')->references('id')->on('komponen_produk_jadis');
            $table->foreign('id_komponen_3')->references('id')->on('komponen_produk_jadis');
            $table->foreign('id_produk_jadi')->references('id')->on('produk_jadis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_uppers');
    }
};
