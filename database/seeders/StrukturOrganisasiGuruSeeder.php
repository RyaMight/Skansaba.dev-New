<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StrukturOrganisasiRpl;

class StrukturOrganisasiGuruSeeder extends Seeder
{
    /**
     * Data guru RPL (sumber: eduroute/rpl), foto dari storage/app/public/struktur-organisasi.
     * Idempotent: update or create by nama.
     */
    public function run(): void
    {
        $guru = [
            [
                'nama' => 'Haris Budiawan, S.Pd.',
                'jabatan' => 'Ketua Program Keahlian PPLG',
                'foto' => 'struktur-organisasi/haris-budiawan.webp',
                'deskripsi_md' => "Kaproli PPLG\nGuru Produktif Keahlian Basis Data & AI\n\nGuru Produktif Rekayasa Perangkat Lunak yang memiliki keahlian di bidang Basis Data dan Artificial Intelligence (AI). Berpengalaman membantu serta memimpin program keahlian dalam implementasi kurikulum berbasis industri terkini.",
            ],
            [
                'nama' => 'Rosyida Ramadhani, S.Pd.',
                'jabatan' => 'Guru Produktif RPL',
                'foto' => 'struktur-organisasi/rosyida-ramadhani.webp',
                'deskripsi_md' => "Keahlian: Mobile & Desktop Programming\n\nGuru Produktif Rekayasa Perangkat Lunak yang memiliki keahlian di bidang Mobile Programming (Flutter/Android) dan Desktop Programming, membina kreativitas siswa dalam merancang aplikasi multi-platform.",
            ],
            [
                'nama' => 'Reny Murni Hidayati, M.Pd.',
                'jabatan' => 'Guru Produktif RPL',
                'foto' => 'struktur-organisasi/reny-murni-hidayati.webp',
                'deskripsi_md' => "Keahlian: UI/UX & Business Plan\n\nGuru Produktif Rekayasa Perangkat Lunak yang memiliki keahlian di bidang Perencanaan Software dan Business Plan. Berfokus pada pengembangan produk digital, desain antarmuka, serta pemecahan masalah dengan pendekatan Think Unblock.",
            ],
            [
                'nama' => 'Dinda Ayu Dewanti, S.Kom.',
                'jabatan' => 'Guru Produktif RPL',
                'foto' => 'struktur-organisasi/dinda-ayu-dewanti.webp',
                'deskripsi_md' => "Keahlian: Web Development & IoT\n\nGuru Produktif Rekayasa Perangkat Lunak yang memiliki keahlian di bidang Website Programming, Database, dan Internet of Things (IoT). Aktif mengembangkan siswa dalam mengembangkan solusi web modern dan integrasi perangkat cerdas.",
            ],
            [
                'nama' => 'Diah Utaminingsih, S.T.',
                'jabatan' => 'Guru Produktif RPL',
                'foto' => 'struktur-organisasi/diah-utaminingsih.webp',
                'deskripsi_md' => "Keahlian: Cyber Security & Jaringan\n\nGuru Produktif Rekayasa Perangkat Lunak yang memiliki keahlian di bidang Cyber Security, Python, dan Administrasi Jaringan. Membimbing siswa dalam aspek keamanan perangkat lunak, skriping otomatisasi, dan administrasi jaringan.",
            ],
            [
                'nama' => 'Rusdiyanto, S.Pd.',
                'jabatan' => 'Guru Produktif RPL',
                'foto' => 'struktur-organisasi/rusdiyanto.webp',
                'deskripsi_md' => "Keahlian: Basis Data & Server\n\nGuru Produktif Rekayasa Perangkat Lunak yang memiliki keahlian mendalam di bidang Elektronika, Web Server, dan Delphi Programming, membimbing siswa dalam pengolahan sistem informasi dan server berbasis desktop.",
            ],
        ];

        foreach ($guru as $i => $g) {
            StrukturOrganisasiRpl::updateOrCreate(
                ['nama' => $g['nama']],
                [
                    'jabatan' => $g['jabatan'],
                    'foto' => $g['foto'],
                    'deskripsi_md' => $g['deskripsi_md'],
                    'order' => $i + 1,
                    'status' => 'published',
                ]
            );
        }

        $this->command->info('Struktur organisasi guru RPL seeded: ' . count($guru) . ' guru.');
    }
}
