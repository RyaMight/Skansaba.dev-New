<?php

use App\Livewire\KaryaSiswaPublic;
use App\Models\KaryaSiswa;
use App\Models\User;
use Livewire\Livewire;

function makeKaryaForProfilSort(User $siswa, string $judul): KaryaSiswa
{
    return KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'judul' => $judul,
        'kategori' => 'Web',
        'kelas' => 'XII RPL 1',
        'tahun' => 2026,
        'status' => 'published',
        'published_at' => now(),
        'deskripsi' => 'Karya untuk pengujian urutan profil siswa.',
    ]);
}

test('profil siswa diurutkan berdasarkan jumlah karya published terbanyak', function () {
    $palingBanyak = User::factory()->create([
        'role' => 'siswa',
        'status' => 'active',
        'username' => 'siswa-paling-banyak',
    ]);
    $sedikit = User::factory()->create([
        'role' => 'siswa',
        'status' => 'active',
        'username' => 'siswa-sedikit',
    ]);
    $tanpaKarya = User::factory()->create([
        'role' => 'siswa',
        'status' => 'active',
        'username' => 'siswa-tanpa-karya',
    ]);

    makeKaryaForProfilSort($palingBanyak, 'Karya A Satu');
    makeKaryaForProfilSort($palingBanyak, 'Karya A Dua');
    makeKaryaForProfilSort($sedikit, 'Karya B Satu');

    Livewire::test(KaryaSiswaPublic::class)
        ->assertSeeInOrder([
            '@siswa-paling-banyak',
            '@siswa-sedikit',
            '@siswa-tanpa-karya',
        ]);
});
