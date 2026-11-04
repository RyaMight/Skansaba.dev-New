<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karya_siswas', function (Blueprint $table) {
            // Pesan dari admin saat karya ditolak (alasan)
            $table->text('catatan_admin')->nullable()->after('tags');
        });
    }

    public function down(): void
    {
        Schema::table('karya_siswas', function (Blueprint $table) {
            $table->dropColumn('catatan_admin');
        });
    }
};
