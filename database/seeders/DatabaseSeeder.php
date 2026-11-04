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

        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@rpl.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $guru = User::create([
            'name' => 'Guru RPL',
            'email' => 'guru@rpl.sch.id',
            'password' => Hash::make('guru123'),
            'role' => 'guru',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $siswa = User::create([
            'name' => 'Siswa RPL (Uji Coba)',
            'nisn' => '0061234567',
            'email' => 'siswa@rpl.sch.id',
            'password' => Hash::make('skansabajaya123'),
            'role' => 'siswa',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Pastikan user aktif (deleted_at bersih) — pencegahan ekstra.
        User::query()->update(['deleted_at' => null]);

        $this->command->info('✓ Users created (3 records)');
        $this->command->info('  ├─ Admin: admin@rpl.sch.id / admin123');
        $this->command->info('  ├─ Guru:  guru@rpl.sch.id / guru123');
        $this->command->info('  └─ Siswa: NISN: 0061234567 / skansabajaya123');
        $this->command->newLine();

        // 2. Seed all content tables
        $this->command->info('📚 Seeding content tables...');
        
        $this->call([
            GlobalSettingSeeder::class,
            LandingPageSliderSeeder::class,
            StrukturOrganisasiRplSeeder::class,
            ProspekKarirSeeder::class,
            KaryaSiswaSeeder::class,
            PrestasiSeeder::class,
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
        $this->command->info('  ├─ Users: 2 records');
        $this->command->info('  ├─ Global Settings: 1 record');
        $this->command->info('  ├─ Landing Page Sliders: 3 records');
        $this->command->info('  ├─ Struktur Organisasi RPL: 7 records');
        $this->command->info('  ├─ Prospek Karir: 6 records');
        $this->command->info('  ├─ Karya Siswa: 6 records');
        $this->command->info('  ├─ Prestasi: 6 records');
        $this->command->info('  ├─ Berita: 6 records');
        $this->command->info('  ├─ Galeri: 6 records');
        $this->command->info('  ├─ Alumni: 6 records');
        $this->command->info('  └─ Mitra: 9 records');
        $this->command->newLine();
    }
}