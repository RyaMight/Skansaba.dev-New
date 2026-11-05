<?php

namespace Database\Seeders;

use App\Models\Prestasi;
use Illuminate\Database\Seeder;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Sinkron dengan https://skansaba.dev - belum ada data prestasi yang dipublikasikan.
     */
    public function run(): void
    {
        $this->call(PrestasiSiswaSeeder::class);
    }
}
