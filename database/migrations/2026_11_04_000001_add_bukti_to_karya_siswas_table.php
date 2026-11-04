<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karya_siswas', function (Blueprint $table) {
            $table->json('bukti')->nullable()->after('url_repo');
        });
    }

    public function down(): void
    {
        Schema::table('karya_siswas', function (Blueprint $table) {
            $table->dropColumn('bukti');
        });
    }
};
