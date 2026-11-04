<?php

use App\Livewire\Components\SidebarMenu;
use App\Models\KaryaSiswa;
use App\Models\User;
use Livewire\Livewire;

test('sidebar displays alert badge on verifikasi karya when review is pending', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = User::factory()->create(['role' => 'siswa']);

    // No pending reviews yet
    Livewire::actingAs($admin)
        ->test(SidebarMenu::class)
        ->assertDontSeeHtml('perlu verifikasi');

    // Create a karya needing review
    KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'judul' => 'Aplikasi Pengaduan',
        'kategori' => 'Web',
        'kelas' => 'XII RPL 1',
        'tahun' => 2026,
        'status' => 'review',
    ]);

    Livewire::actingAs($admin)
        ->test(SidebarMenu::class)
        ->assertSeeHtml('perlu verifikasi')
        ->assertSeeHtml('!');
});
