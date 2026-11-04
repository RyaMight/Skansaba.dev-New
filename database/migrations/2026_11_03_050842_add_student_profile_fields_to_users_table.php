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
        Schema::table('users', function (Blueprint $table) {
            $table->string('kelas', 50)->nullable()->after('name');
            $table->text('bio')->nullable()->after('kelas');
            $table->string('phone', 30)->nullable()->after('bio');
            $table->string('keahlian', 255)->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'bio', 'phone', 'keahlian']);
        });
    }
};
