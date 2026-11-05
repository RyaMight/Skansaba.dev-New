<?php

use App\Livewire\KaryaSiswaManager;
use App\Livewire\KaryaSiswaVerifikasi;
use App\Models\KaryaLog;
use App\Models\KaryaSiswa;
use App\Models\User;
use Livewire\Livewire;

test('karya saya displays strictly 4 menus for siswa: draft, sedang di review, terpublikasi, keterangan', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
    ]);

    KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Karya Draf 1',
        'kategori' => 'web',
        'deskripsi' => 'Deskripsi draf.',
        'status' => 'draft',
    ]);

    KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Karya Review 1',
        'kategori' => 'mobile',
        'deskripsi' => 'Deskripsi review.',
        'status' => 'review',
    ]);

    KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Karya Publik 1',
        'kategori' => 'game',
        'deskripsi' => 'Deskripsi publik.',
        'status' => 'published',
    ]);

    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->assertSee('Draft')
        ->assertSee('Sedang di Review')
        ->assertSee('Terpublikasi')
        ->assertSee('Keterangan')
        ->assertDontSee('Semua Karya'); // Siswa view strictly does not have 'Semua Karya' menu card
});

test('keterangan log displays reasons for rejection and approval info', function () {
    $admin = User::factory()->create(['role' => 'admin', 'name' => 'Guru Penguji']);
    $siswa = User::factory()->create(['role' => 'siswa', 'name' => 'Kalis Lintang Pradipta']);

    $karya = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Aplikasi Pembayaran SPP',
        'kategori' => 'web',
        'deskripsi' => 'Aplikasi SPP siswa RPL.',
        'status' => 'review',
    ]);

    // Admin rejects with reason
    Livewire::actingAs($admin)
        ->test(KaryaSiswaVerifikasi::class)
        ->set('rejectNotes.' . $karya->id, 'Tampilan antarmuka belum responsif di mobile dan bukti orisinalitas kurang.')
        ->set('rejectingId', $karya->id)
        ->call('confirmReject');

    // Verify KaryaLog recorded
    $log = KaryaLog::where('user_id', $siswa->id)->latest('id')->first();
    expect($log)->not->toBeNull();
    expect($log->status)->toBe('rejected');
    expect($log->catatan)->toContain('Tampilan antarmuka belum responsif');

    // Siswa opens Keterangan menu in Karya Saya
    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('setActiveMenu', 'keterangan')
        ->assertSet('activeMenu', 'keterangan')
        ->assertSee('Keterangan & Riwayat Status Karya')
        ->assertSee('Ditolak / Butuh Revisi')
        ->assertSee('Aplikasi Pembayaran SPP')
        ->assertSee('Tampilan antarmuka belum responsif di mobile dan bukti orisinalitas kurang.')
        ->assertSee('Perbaiki & Edit Karya');
});

test('karya log retains strictly maximum 5 entries and automatically deletes oldest entries', function () {
    $siswa = User::factory()->create(['role' => 'siswa']);

    // Create 7 logs for the student
    for ($i = 1; $i <= 7; $i++) {
        KaryaLog::record(
            userId: $siswa->id,
            judulKarya: "Karya Log Ke-{$i}",
            status: 'draft',
            action: 'draft_created',
            catatan: "Catatan log ke-{$i}"
        );
    }

    // Must strictly be 5 logs
    $userLogs = KaryaLog::where('user_id', $siswa->id)->orderBy('id', 'asc')->get();
    expect($userLogs->count())->toBe(5);

    // Oldest logs (Log 1 and Log 2) must be deleted; Log 3 to 7 remain
    expect($userLogs->pluck('judul_karya')->toArray())->toEqual([
        'Karya Log Ke-3',
        'Karya Log Ke-4',
        'Karya Log Ke-5',
        'Karya Log Ke-6',
        'Karya Log Ke-7',
    ]);
});
