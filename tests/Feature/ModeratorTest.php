<?php

use App\Livewire\Admin\SiswaRplManager;
use App\Livewire\Components\SidebarMenu;
use App\Livewire\KaryaSiswaVerifikasi;
use App\Livewire\Siswa\ModeratorKeamanan;
use App\Livewire\Siswa\ProfilDashboard;
use App\Livewire\UserManager;
use App\Models\KaryaSiswa;
use App\Models\LoginAudit;
use App\Models\ModerationLog;
use App\Models\User;
use Livewire\Livewire;

function makeKarya(User $siswa, string $judul, string $status = 'review'): KaryaSiswa
{
    return KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'judul' => $judul,
        'kategori' => 'Web',
        'kelas' => 'XII RPL 1',
        'tahun' => 2026,
        'status' => $status,
        'deskripsi' => 'Deskripsi karya untuk pengujian moderator.',
    ]);
}

test('admin dapat memberikan dan mencabut status moderator dari akun siswa rpl', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($admin)
        ->test(SiswaRplManager::class)
        ->call('grantModerator', $siswa->id)
        ->assertHasNoErrors();

    expect($siswa->fresh()->isModerator())->toBeTrue();
    expect(ModerationLog::where('action', 'moderator.granted')->where('target_user_id', $siswa->id)->exists())->toBeTrue();

    Livewire::actingAs($admin)
        ->test(SiswaRplManager::class)
        ->call('revokeModerator', $siswa->id);

    expect($siswa->fresh()->isModerator())->toBeFalse();
    expect(ModerationLog::where('action', 'moderator.revoked')->where('target_user_id', $siswa->id)->exists())->toBeTrue();
});

test('guru tidak dapat memberikan status moderator', function () {
    $guru = User::factory()->create(['role' => 'guru']);
    $siswa = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($guru)
        ->test(SiswaRplManager::class)
        ->call('grantModerator', $siswa->id)
        ->assertForbidden();

    expect($siswa->fresh()->isModerator())->toBeFalse();
});

test('admin dapat mengatur status moderator lewat manajemen pengguna', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $siswa = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('openEditModal', $siswa->id)
        ->set('is_moderator', true)
        ->call('update')
        ->assertHasNoErrors();

    expect($siswa->fresh()->isModerator())->toBeTrue();

    // Mengubah role keluar dari siswa otomatis mencabut status moderator.
    Livewire::actingAs($admin)
        ->test(UserManager::class)
        ->call('openEditModal', $siswa->id)
        ->set('role', 'guru')
        ->call('update')
        ->assertHasNoErrors();

    $siswa->refresh();
    expect($siswa->role)->toBe('guru');
    expect($siswa->is_moderator)->toBeFalse();
});

test('moderator dapat mengakses verifikasi karya tetapi tidak melihat karya sendiri', function () {
    $moderator = User::factory()->create(['role' => 'siswa', 'is_moderator' => true]);
    $siswaLain = User::factory()->create(['role' => 'siswa']);

    makeKarya($moderator, 'Karya Milik Moderator');
    makeKarya($siswaLain, 'Karya Siswa Lain');

    Livewire::actingAs($moderator)
        ->test(KaryaSiswaVerifikasi::class)
        ->assertSee('Karya Siswa Lain')
        ->assertDontSee('Karya Milik Moderator')
        ->assertSee('Mode Moderator');
});

test('siswa biasa tidak dapat mengakses verifikasi karya moderator', function () {
    $siswa = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($siswa)
        ->test(KaryaSiswaVerifikasi::class)
        ->assertForbidden();
});

test('moderator dapat menyetujui karya siswa lain dan memberikan tag terverifikasi', function () {
    $moderator = User::factory()->create(['role' => 'siswa', 'is_moderator' => true]);
    $siswaLain = User::factory()->create(['role' => 'siswa']);

    $karya = makeKarya($siswaLain, 'Aplikasi Kasir Sekolah');

    Livewire::actingAs($moderator)
        ->test(KaryaSiswaVerifikasi::class)
        ->call('selectTag', $karya->id, 'Web Development')
        ->call('openApproveModal', $karya->id)
        ->call('confirmApprove')
        ->assertHasNoErrors();

    $karya->refresh();
    $siswaLain->refresh();

    expect($karya->status)->toBe('published');
    expect($siswaLain->hasTag('Web Development'))->toBeTrue();
    expect(ModerationLog::where('action', 'karya.approved')->where('target_user_id', $siswaLain->id)->exists())->toBeTrue();
});

test('moderator tidak dapat menyetujui karya miliknya sendiri', function () {
    $moderator = User::factory()->create(['role' => 'siswa', 'is_moderator' => true]);
    $karya = makeKarya($moderator, 'Karya Sendiri Moderator');

    Livewire::actingAs($moderator)
        ->test(KaryaSiswaVerifikasi::class)
        ->call('openApproveModal', $karya->id)
        ->assertSet('approvingId', null)
        ->call('confirmApprove');

    expect($karya->fresh()->status)->toBe('review');
});

test('moderator dapat mengakses halaman keamanan dan mengunci akun siswa lain', function () {
    $moderator = User::factory()->create(['role' => 'siswa', 'is_moderator' => true]);
    $siswaLain = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($moderator)
        ->test(ModeratorKeamanan::class)
        ->assertSee('Keamanan')
        ->assertSee('Audit')
        ->call('lockAccount', $siswaLain->id)
        ->assertHasNoErrors();

    expect($siswaLain->fresh()->status)->toBe('inactive');
    expect(ModerationLog::where('action', 'account.locked')->where('target_user_id', $siswaLain->id)->exists())->toBeTrue();

    Livewire::actingAs($moderator)
        ->test(ModeratorKeamanan::class)
        ->call('unlockAccount', $siswaLain->id);

    expect($siswaLain->fresh()->status)->toBe('active');
});

test('moderator dapat membuka blokir login sementara siswa', function () {
    $moderator = User::factory()->create(['role' => 'siswa', 'is_moderator' => true]);
    $siswaLain = User::factory()->create(['role' => 'siswa', 'email' => 'target@example.com']);

    foreach (range(1, 5) as $i) {
        LoginAudit::create([
            'email' => $siswaLain->email,
            'status' => 'failed',
            'ip_address' => '127.0.0.1',
            'failure_reason' => 'Invalid credentials',
            'attempted_at' => now()->subMinutes($i),
        ]);
    }

    Livewire::actingAs($moderator)
        ->test(ModeratorKeamanan::class)
        ->call('clearLoginLock', $siswaLain->id);

    expect(LoginAudit::failed()->where('email', $siswaLain->email)->count())->toBe(0);
    expect(ModerationLog::where('action', 'login.unlocked')->where('target_user_id', $siswaLain->id)->exists())->toBeTrue();
});

test('moderator tidak dapat mengelola akun sendiri, admin, atau guru', function () {
    $moderator = User::factory()->create(['role' => 'siswa', 'is_moderator' => true]);
    $admin = User::factory()->create(['role' => 'admin']);
    $guru = User::factory()->create(['role' => 'guru']);

    Livewire::actingAs($moderator)
        ->test(ModeratorKeamanan::class)
        ->call('lockAccount', $moderator->id)
        ->assertForbidden();

    Livewire::actingAs($moderator)
        ->test(ModeratorKeamanan::class)
        ->call('lockAccount', $admin->id)
        ->assertForbidden();

    Livewire::actingAs($moderator)
        ->test(ModeratorKeamanan::class)
        ->call('lockAccount', $guru->id)
        ->assertForbidden();
});

test('siswa biasa tidak dapat mengakses halaman keamanan moderator', function () {
    $siswa = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($siswa)
        ->test(ModeratorKeamanan::class)
        ->assertForbidden();
});

test('menu moderator hanya muncul untuk siswa yang berstatus moderator', function () {
    $moderator = User::factory()->create(['role' => 'siswa', 'is_moderator' => true]);
    $siswa = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($moderator)
        ->test(SidebarMenu::class)
        ->assertSee('Verifikasi Karya')
        ->assertSee('Keamanan')
        ->assertSee('Siswa · Moderator');

    Livewire::actingAs($siswa)
        ->test(SidebarMenu::class)
        ->assertDontSee('Verifikasi Karya')
        ->assertDontSee('Siswa · Moderator');
});

test('panel moderator tampil di dashboard siswa hanya untuk moderator', function () {
    $moderator = User::factory()->create(['role' => 'siswa', 'is_moderator' => true]);
    $siswa = User::factory()->create(['role' => 'siswa']);

    Livewire::actingAs($moderator)
        ->test(ProfilDashboard::class)
        ->assertSee('Panel Moderator')
        ->assertSee('Verifikasi Karya')
        ->assertSee('Keamanan & Audit', false);

    Livewire::actingAs($siswa)
        ->test(ProfilDashboard::class)
        ->assertDontSee('Panel Moderator');
});

test('profil publik menampilkan badge moderator khusus', function () {
    $moderator = User::factory()->create([
        'role' => 'siswa',
        'is_moderator' => true,
        'username' => 'moderator-siswa',
    ]);
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'username' => 'siswa-biasa',
    ]);

    $this->get(route('profil.public', $moderator->username))
        ->assertOk()
        ->assertSee('Moderator');

    $this->get(route('profil.public', $siswa->username))
        ->assertOk()
        ->assertDontSee('Moderator');
});
