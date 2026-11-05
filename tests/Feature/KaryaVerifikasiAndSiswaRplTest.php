<?php

use App\Livewire\Admin\SiswaRplManager;
use App\Livewire\KaryaSiswaVerifikasi;
use App\Models\KaryaSiswa;
use App\Models\User;
use Livewire\Livewire;

test('verifikasi karya allows preview modal and gives tag to student without wiping existing tags', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Fajar Pratama',
        'keahlian' => 'UI/UX Design', // Siswa already has 1 verified tag
    ]);

    $karya = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'judul' => 'Sistem Presensi RFID',
        'kategori' => 'IoT',
        'kelas' => 'XII RPL 2',
        'tahun' => 2026,
        'status' => 'review',
        'deskripsi' => 'Aplikasi IoT presensi berbasis kartu RFID.',
    ]);

    // Admin opens verification page
    Livewire::actingAs($admin)
        ->test(KaryaSiswaVerifikasi::class)
        ->assertDontSeeHtml('Pratinjau ↗')
        ->assertSeeHtml('Lihat Preview Lengkap')
        ->call('openPreviewModal', $karya->id)
        ->assertSet('previewId', $karya->id)
        ->assertSeeHtml('Preview Portofolio')
        ->call('closePreviewModal')
        ->assertSet('previewId', null)
        ->call('selectTag', $karya->id, 'IoT & Embedded')
        ->assertSet('selectedTag.' . $karya->id, 'IoT & Embedded')
        ->call('openApproveModal', $karya->id)
        ->assertSet('approvingId', $karya->id)
        ->assertSeeHtml('Konfirmasi Publikasi Karya')
        ->call('confirmApprove')
        ->assertSet('approvingId', null);

    // Assert database updated: student now has BOTH tags!
    $karya->refresh();
    $siswa->refresh();

    expect($karya->status)->toBe('published');
    expect($karya->tags)->toBe(['IoT & Embedded']);
    expect($siswa->hasTag('UI/UX Design'))->toBeTrue();
    expect($siswa->hasTag('IoT & Embedded'))->toBeTrue();
    expect($siswa->keahlian_list)->toContain('UI/UX Design', 'IoT & Embedded');
});

test('siswa rpl manager displays students and allows admin to give and revoke tags', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Budi Santoso',
        'kelas' => 'XII RPL 1',
        'keahlian' => 'Web Development',
    ]);

    // Admin gives an additional tag
    Livewire::actingAs($admin)
        ->test(SiswaRplManager::class)
        ->assertSee('Budi Santoso')
        ->call('giveTag', $siswa->id, 'Mobile Development');

    $siswa->refresh();
    expect($siswa->hasTag('Web Development'))->toBeTrue();
    expect($siswa->hasTag('Mobile Development'))->toBeTrue();
    expect(count($siswa->keahlian_list))->toBe(2);

    // Admin revokes the first tag
    Livewire::actingAs($admin)
        ->test(SiswaRplManager::class)
        ->call('revokeTag', $siswa->id, 'Web Development');

    $siswa->refresh();
    expect($siswa->hasTag('Web Development'))->toBeFalse();
    expect($siswa->hasTag('Mobile Development'))->toBeTrue();
    expect($siswa->keahlian_list)->toBe(['Mobile Development']);
});

test('admin can create new student with strictly role siswa in siswa rpl manager', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Admin creates new student (role automatically strictly 'siswa')
    Livewire::actingAs($admin)
        ->test(SiswaRplManager::class)
        ->call('openCreateModal')
        ->set('name', 'Rina Wulandari')
        ->set('nisn', '0089876543')
        ->set('kelas', 'XI RPL 2')
        ->set('email', 'rina.wulandari@skansaba.sch.id')
        ->set('password', 'password123')
        ->set('status', 'active')
        ->set('keahlian', 'UI/UX Design')
        ->call('createStudent')
        ->assertHasNoErrors();

    $newStudent = User::where('email', 'rina.wulandari@skansaba.sch.id')->first();
    expect($newStudent)->not->toBeNull();
    expect($newStudent->name)->toBe('Rina Wulandari');
    expect($newStudent->role)->toBe('siswa');
    expect($newStudent->keahlian)->toBe('UI/UX Design');
});

test('non admin or guru cannot access siswa rpl manager', function () {
    $siswa = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($siswa)
        ->test(SiswaRplManager::class)
        ->assertForbidden();
});

test('public karya siswa page displays real 35 siswa profiles and both published and dummy karya', function () {
    $this->seed(\Database\Seeders\GlobalSettingSeeder::class);
    $this->seed(\Database\Seeders\SiswaRplSeeder::class);
    $this->seed(\Database\Seeders\KaryaSiswaSeeder::class);

    $kalis = User::where('nisn', '18346')->firstOrFail();

    // Publish a new karya by a student and ensure it appears on the public page
    $newKarya = KaryaSiswa::create([
        'user_id' => $kalis->id,
        'nama_siswa' => $kalis->name,
        'judul' => 'Skansaba DevOps Monitor',
        'kategori' => 'Web Development',
        'teknologi' => ['Laravel', 'Docker'],
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'status' => 'published',
        'published_at' => now()->addMinute(),
        'deskripsi' => 'Aplikasi pemantauan container server sekolah.',
    ]);

    Livewire::test(\App\Livewire\KaryaSiswaPublic::class)
        // Real 35 students appear in Profil Siswa
        ->assertSee('Alief Saktiawan Prananda')
        ->assertSee('Kalis Lintang Pradipta')
        ->assertSee('Zakian Akbar Maulana')
        ->assertSee($kalis->profil_url)
        // NISN should not be displayed in karya / profil siswa public view
        ->assertDontSee($kalis->nisn)
        // Real published karya (seeded + newly published) appear in Karya Siswa
        ->assertSee('Skansaba DevOps Monitor')
        ->assertSee('Kelas Angkasa')
        ->assertSee(route('karya.detail', $newKarya->id))
        // Retained dummy karya also appears
        ->assertSee('Sistem Informasi Perpustakaan');
});
