<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SiswaRplSeeder extends Seeder
{
    /**
     * Data 35 akun siswa RPL beserta NIS/NISN dan password default SKANSABAJAYA321.
     * Idempotent: aman dijalankan berulang kali dengan:
     *   php artisan db:seed --class=SiswaRplSeeder
     * atau melalui:
     *   php artisan db:seed
     */
    public const SISWA_LIST = [
        ['nisn' => '18332', 'name' => 'Alief Saktiawan Prananda'],
        ['nisn' => '18333', 'name' => 'Arya Saputra Nugraha'],
        ['nisn' => '18334', 'name' => 'Azka Chirza Saifani Chasan'],
        ['nisn' => '18335', 'name' => 'Dafa Fadhila Dzikri'],
        ['nisn' => '18336', 'name' => 'Dafa Muhammad Sulistyo'],
        ['nisn' => '18338', 'name' => 'Dini Nur Meilia'],
        ['nisn' => '18339', 'name' => 'Fahrezy Ramadinansya'],
        ['nisn' => '18340', 'name' => 'Fairuz Adam Rizqulah'],
        ['nisn' => '18341', 'name' => 'Faiz Ibnu Majid'],
        ['nisn' => '18342', 'name' => 'Fandy Miftakhul Zanuar'],
        ['nisn' => '18343', 'name' => 'Fassa Hutama Nurrofiqi'],
        ['nisn' => '18344', 'name' => 'Hasan Nur Adhi Wibowo'],
        ['nisn' => '18345', 'name' => 'Hilal Nabiil Mukhtar Prasetya'],
        ['nisn' => '18346', 'name' => 'Kalis Lintang Pradipta'],
        ['nisn' => '18347', 'name' => 'Khansa Maysun Zuhayr'],
        ['nisn' => '18348', 'name' => 'Kusumastuti Endah Retnadi'],
        ['nisn' => '18349', 'name' => 'Labib Arafat'],
        ['nisn' => '18350', 'name' => 'Laili Nur Azizah'],
        ['nisn' => '18351', 'name' => 'Maden Haryo Wicaksono'],
        ['nisn' => '18352', 'name' => 'Muhammad Aziz Nugroho'],
        ['nisn' => '18353', 'name' => 'Muhammad Fahmi Muqorrobin'],
        ['nisn' => '18354', 'name' => 'Muhammad Raditya Aqso Hikmawan'],
        ['nisn' => '18355', 'name' => 'Muhammad Tsaqif Abdulloh'],
        ['nisn' => '18356', 'name' => 'Nafiza Bilqis Syarifah'],
        ['nisn' => '18357', 'name' => 'Paty Helmy Tamami Lamadira'],
        ['nisn' => '18358', 'name' => 'Resty Budi Lestari'],
        ['nisn' => '18359', 'name' => 'Rifky Dhafa Putra Triwardhana'],
        ['nisn' => '18360', 'name' => 'Rifqi Nata Kalbu Shafwan'],
        ['nisn' => '18361', 'name' => 'Rindra Putra Agustin'],
        ['nisn' => '18362', 'name' => 'Rishafy Askha Septama'],
        ['nisn' => '18363', 'name' => 'Sabhinaya Aisha Saraswati'],
        ['nisn' => '18364', 'name' => 'Syakira Nawa Ayyasy'],
        ['nisn' => '18365', 'name' => 'Syandana Reska Josawa'],
        ['nisn' => '18366', 'name' => 'Winasis Prasojo'],
        ['nisn' => '18367', 'name' => 'Zakian Akbar Maulana'],
    ];

    public function run(): void
    {
        // Hapus akun dummy Siswa RPL (Uji Coba) jika masih ada di database
        User::withTrashed()
            ->where('nisn', '0061234567')
            ->orWhere('email', 'siswa@rpl.sch.id')
            ->forceDelete();

        // Sinkronkan password default Admin & Guru jika dijalankan via SiswaRplSeeder
        User::where('email', 'admin@rpl.sch.id')->update([
            'password' => Hash::make(User::DEFAULT_ADMIN_PASSWORD),
        ]);
        User::where('email', 'guru@rpl.sch.id')->update([
            'password' => Hash::make(User::DEFAULT_GURU_PASSWORD),
        ]);

        $defaultPasswordHash = Hash::make(User::DEFAULT_SISWA_PASSWORD);

        foreach (self::SISWA_LIST as $item) {
            $nisn = $item['nisn'];
            $name = $item['name'];
            $defaultUsername = Str::slug($name);
            $defaultEmail = $nisn . '@siswa.rpl.sch.id';

            $existing = User::withTrashed()->where('nisn', $nisn)->first();

            if ($existing) {
                if ($existing->trashed()) {
                    $existing->restore();
                }

                $existing->update([
                    'name' => $name,
                    'role' => 'siswa',
                    'status' => 'active',
                    'kelas' => $existing->kelas ?: 'XI RPL 1',
                    'username' => $existing->username ?: $defaultUsername,
                    'email' => $existing->email ?: $defaultEmail,
                ]);
            } else {
                // Pastikan username unik
                $username = $defaultUsername;
                $counter = 1;
                while (User::withTrashed()->where('username', $username)->exists()) {
                    $username = $defaultUsername . '-' . $counter++;
                }

                User::create([
                    'name' => $name,
                    'nisn' => $nisn,
                    'kelas' => 'XI RPL 1',
                    'username' => $username,
                    'email' => $defaultEmail,
                    'password' => $defaultPasswordHash,
                    'role' => 'siswa',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }
        }

        if ($this->command) {
            $this->command->info('✓ Siswa RPL seeded (' . count(self::SISWA_LIST) . ' akun siswa, default password: ' . User::DEFAULT_SISWA_PASSWORD . ')');
        }
    }
}
