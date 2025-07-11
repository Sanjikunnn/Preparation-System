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
        Schema::create('produk_jadis', function (Blueprint $table) {
            $table->string('id')->primary(); // ubah dari $table->id()
            $table->string('nama_produk_jadi');
            $table->string('id_komponen_produk_jadi_1');
            $table->string('id_komponen_produk_jadi_2');
            $table->string('id_komponen_produk_jadi_3');
            $table->integer('qty');
            $table->timestamps();

            $table->foreign('id_komponen_produk_jadi_1')->references('id')->on('komponen_produk_jadis');
            $table->foreign('id_komponen_produk_jadi_2')->references('id')->on('komponen_produk_jadis');
            $table->foreign('id_komponen_produk_jadi_3')->references('id')->on('komponen_produk_jadis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_jadis');
    }
};
