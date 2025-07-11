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
        Schema::create('komponen_produk_jadis', function (Blueprint $table) {
            $table->string('id')->primary(); // ubah dari $table->id() ke string
            $table->string('nama_komponen_produk_jadi');
            $table->integer('qty');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_produk_jadis');
    }
};
