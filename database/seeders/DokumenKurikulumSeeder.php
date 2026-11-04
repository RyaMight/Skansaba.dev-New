<?php

namespace Database\Seeders;

use App\Models\DokumenKurikulum;
use Illuminate\Database\Seeder;

class DokumenKurikulumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data disinkronkan dari https://skansaba.dev
     * File PDF sudah tersedia di storage/app/public/documents/
     */
    public function run(): void
    {
        DokumenKurikulum::truncate();

        $admin = \App\Models\User::where('role', 'admin')->first();

        if (!$admin) {
            $admin = \App\Models\User::firstOrCreate(
                ['email' => 'admin@rpl.sch.id'],
                [
                    'name' => 'Administrator',
                    'password' => \Hash::make('admin123'),
                    'role' => 'admin',
                    'status' => 'active',
                ]
            );
        }

        $dokumen = [
            [
                'judul' => 'Profil RPL',
                'jenis' => 'lainnya',
                'tahun_berlaku' => 2026,
                'file' => 'documents/profil_rpl_1778205422.pdf',
                'ukuran_file' => 4171708,
            ],
        ];

        foreach ($dokumen as $data) {
            if ($data['file'] && !\Storage::disk('public')->exists($data['file'])) {
                $this->command->warn("File tidak ditemukan, lewati: {$data['file']}");
                $data['file'] = null;
            }

            DokumenKurikulum::create([
                'user_id' => $admin->id,
                'judul' => $data['judul'],
                'jenis' => $data['jenis'],
                'tahun_berlaku' => $data['tahun_berlaku'],
                'file' => $data['file'],
                'ukuran_file' => $data['ukuran_file'],
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        $this->command->info('✓ Dokumen Kurikulum seeded successfully (' . count($dokumen) . ' records) - data skansaba.dev');
    }
}
