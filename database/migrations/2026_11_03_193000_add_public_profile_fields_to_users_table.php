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
            // Headline singkat di bawah nama (contoh: "Fullstack Web Developer | Siswa Kelas XII RPL 1")
            $table->string('headline', 120)->nullable()->after('bio');

            // README profil ala GitHub (markdown, bebas dihias oleh siswa)
            $table->text('profil_readme')->nullable()->after('headline');

            // URL kustom profil publik ala GitHub username (misal: /profil/ahmad-syahputra)
            // Unik & mudah dibagikan ke HRD
            $table->string('username', 50)->nullable()->unique()->after('profil_readme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['headline', 'profil_readme', 'username']);
        });
    }
};
