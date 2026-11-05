<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        $this->command->newLine();

        // Disable foreign key checks (works on MySQL & SQLite)
        \Schema::disableForeignKeyConstraints();

        // 1. Create Users (Admin & Guru)
        $this->command->info('👤 Creating users...');

        // Hard delete semua user (termasuk yang soft-deleted) supaya tidak ada
        // user "hantu" yang membuat login gagal setelah re-seed.
        User::withTrashed()->forceDelete();
        \App\Models\LoginAudit::truncate();

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@rpl.sch.id',
            'password' => Hash::make(User::DEFAULT_ADMIN_PASSWORD),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $guru = User::create([
            'name' => 'Guru RPL',
            'email' => 'guru@rpl.sch.id',
            'password' => Hash::make(User::DEFAULT_GURU_PASSWORD),
            'role' => 'guru',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Seed 35 akun siswa RPL
        $this->call(SiswaRplSeeder::class);

        // Pastikan user aktif (deleted_at bersih) — pencegahan ekstra.
        User::query()->update(['deleted_at' => null]);

        $this->command->info('✓ Users created (Admin, Guru + 35 Siswa RPL)');
        $this->command->info('  ├─ Admin: admin@rpl.sch.id / ' . User::DEFAULT_ADMIN_PASSWORD);
        $this->command->info('  ├─ Guru:  guru@rpl.sch.id / ' . User::DEFAULT_GURU_PASSWORD);
        $this->command->info('  └─ Siswa: NIS (18332-18367) / ' . User::DEFAULT_SISWA_PASSWORD);
        $this->command->newLine();

        // 2. Seed all content tables
        $this->command->info('📚 Seeding content tables...');
        
        $this->call([
            GlobalSettingSeeder::class,
            LandingPageSliderSeeder::class,
            StrukturOrganisasiGuruSeeder::class,
            ProspekKarirSeeder::class,
            KaryaSiswaSeeder::class,
            PrestasiSiswaSeeder::class,
            BeritaSeeder::class,
            GaleriSeeder::class,
            AlumniSeeder::class,
            MitraSeeder::class,
            DokumenKurikulumSeeder::class,
        ]);
        
            
        // Re-enable foreign key checks (works on MySQL & SQLite)
        \Schema::enableForeignKeyConstraints();

        $this->command->newLine();
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('  ├─ Users: 37 records (1 Admin, 1 Guru, 35 Siswa RPL)');
        $this->command->info('  ├─ Global Settings: 1 record');
        $this->command->info('  ├─ Landing Page Sliders: 8 records');
        $this->command->info('  ├─ Struktur Organisasi RPL: 6 records');
        $this->command->info('  ├─ Prospek Karir: 7 records');
        $this->command->info('  ├─ Karya Siswa: 8 records');
        $this->command->info('  ├─ Prestasi Siswa: 8 records');
        $this->command->info('  ├─ Berita: 3 records');
        $this->command->info('  ├─ Galeri: 0 records');
        $this->command->info('  ├─ Alumni: 11 records');
        $this->command->info('  ├─ Dokumen Kurikulum: 1 record');
        $this->command->info('  └─ Mitra: 13 records');
        $this->command->newLine();
    }
}