<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karya_siswas', function (Blueprint $table) {
            // Ringkasan 1-2 kalimat untuk kartu karya (sebelum di-click)
            $table->string('deskripsi_singkat', 300)->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('karya_siswas', function (Blueprint $table) {
            $table->dropColumn('deskripsi_singkat');
        });
    }
};
