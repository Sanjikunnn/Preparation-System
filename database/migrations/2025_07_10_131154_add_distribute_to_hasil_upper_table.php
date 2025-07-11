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
            $table->boolean('distribute')->default(false)->after('barcode_image');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_uppers', function (Blueprint $table) {
            $table->dropColumn('distribute');
        });
    }

};
