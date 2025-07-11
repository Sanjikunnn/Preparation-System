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
        Schema::table('hasil_uppers', function (Blueprint $table) {
        $table->integer('total_uppers')->default(0)->after('total_proses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_uppers', function (Blueprint $table) {
        $table->dropColumn('total_uppers');
        });
    }
};
