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
        Schema::create('galeri_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galeri_id')->constrained('galeris')->onDelete('cascade');
            $table->string('file_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // We can keep the 'gambar' column in galeris as the cover image, 
        // or we can remove it. Let's keep it for compatibility for now.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri_images');
    }
};
