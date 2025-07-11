<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_uppers', function (Blueprint $table) {
            $table->string('barcode_image')->nullable()->after('no_barcode');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_uppers', function (Blueprint $table) {
            $table->dropColumn('barcode_image');
        });
    }
};
