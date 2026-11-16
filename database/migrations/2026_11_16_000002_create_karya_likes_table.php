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
        Schema::create('karya_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karya_siswa_id')->constrained('karya_siswas')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('guest_token', 64)->nullable();
            $table->timestamps();

            $table->unique(['karya_siswa_id', 'user_id']);
            $table->unique(['karya_siswa_id', 'guest_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya_likes');
    }
};