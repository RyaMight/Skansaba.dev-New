<?php

use App\Livewire\KaryaSiswaManager;
use App\Models\KaryaSiswa;
use App\Models\User;
use Livewire\Livewire;

test('karya siswa manager shows only view detail and delete actions with redesigned UI and status explanations', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Dimas Anggara',
    ]);

    $karya = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'judul' => 'Portal Magang Industri',
        'kategori' => 'web',
        'kelas' => 'XII RPL 1',
        'tahun' => 2026,
        'status' => 'published',
        'deskripsi' => 'Aplikasi web manajemen magang siswa.',
    ]);

    $karyaReview = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'judul' => 'Aplikasi POS Cafe',
        'kategori' => 'desktop',
        'kelas' => 'XII RPL 1',
        'tahun' => 2026,
        'status' => 'review',
        'deskripsi' => 'Aplikasi kasir cafe desktop.',
    ]);

    // Admin opens Karya Siswa Manager
    Livewire::actingAs($admin)
        ->test(KaryaSiswaManager::class)
        ->assertSee('Portal Magang Industri')
        ->assertSee('Dimas Anggara')
        ->assertSeeHtml('Lihat Detail')
        ->assertSeeHtml('Hapus')
        ->assertDontSeeHtml('Tambah Karya')
        ->assertDontSeeHtml('Edit')
        // Status explanation assertions
        ->assertSee('Sudah Terpublikasi')
        ->assertSee('Butuh Verifikasi')
        // Test open detail modal for published karya
        ->call('openDetailModal', $karya->id)
        ->assertSet('showDetailModal', true)
        ->assertSee('Status: Karya Sudah Terpublikasi (Live)')
        ->assertSee('Aplikasi web manajemen magang siswa.')
        ->call('closeDetailModal')
        ->assertSet('showDetailModal', false)
        // Test open detail modal for review karya
        ->call('openDetailModal', $karyaReview->id)
        ->assertSet('showDetailModal', true)
        ->assertSee('Status: Butuh Verifikasi (Dalam Review)')
        ->call('closeDetailModal')
        // Test delete confirmation modal
        ->call('confirmDelete', $karya->id)
        ->assertSet('confirmingDeleteId', $karya->id)
        ->assertSeeHtml('Konfirmasi Hapus Karya')
        ->call('delete')
        ->assertSet('confirmingDeleteId', null);

    expect(KaryaSiswa::find($karya->id))->toBeNull();
});
