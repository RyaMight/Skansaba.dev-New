<?php

namespace Database\Seeders;

use App\Models\StrukturOrganisasiRpl;
use Illuminate\Database\Seeder;

class StrukturOrganisasiRplSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Sinkron dengan https://skansaba.dev - belum ada data struktur organisasi yang dipublikasikan.
     */
    public function run(): void
    {
        StrukturOrganisasiRpl::truncate();

        $this->command->info('✓ Struktur Organisasi RPL seeded (0 records) - kosong sesuai skansaba.dev');
    }
}
