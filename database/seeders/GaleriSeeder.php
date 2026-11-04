<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Sinkron dengan https://skansaba.dev - belum ada galeri yang dipublikasikan.
     */
    public function run(): void
    {
        Galeri::truncate();

        $this->command->info('✓ Galeri seeded (0 records) - kosong sesuai skansaba.dev');
    }
}
