<?php

use App\Livewire\Components\SidebarMenu;
use App\Livewire\Settings;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('siswa with default password SKANSABAJAYA321 sees warning popup and sidebar alert badge', function () {
    $siswa = User::factory()->create([
        'name' => 'Siswa Default Password',
        'nisn' => '0089991111',
        'email' => 'siswa.default@rpl.sch.id',
        'role' => 'siswa',
        'status' => 'active',
        'password' => Hash::make(User::DEFAULT_SISWA_PASSWORD),
    ]);

    expect($siswa->isUsingDefaultPassword())->toBeTrue();

    // Check dashboard renders popup warning
    $this->actingAs($siswa)
        ->get(route('siswa.profil'))
        ->assertOk()
        ->assertSee('Segera Ganti Password Anda')
        ->assertSee(User::DEFAULT_SISWA_PASSWORD);

    // Check sidebar menu has alert badge on Pengaturan
    Livewire::actingAs($siswa)
        ->test(SidebarMenu::class)
        ->assertSee('Pengaturan')
        ->assertViewHas('menuItems', function (array $items) {
            $settingsItem = collect($items)->firstWhere('key', 'settings');
            return !empty($settingsItem['has_badge']) && $settingsItem['badge_count'] === '!';
        });
});

test('siswa cannot change password to default password and can change to a new valid password', function () {
    $siswa = User::factory()->create([
        'name' => 'Siswa Ganti Password',
        'nisn' => '0089992222',
        'email' => 'siswa.ganti@rpl.sch.id',
        'role' => 'siswa',
        'status' => 'active',
        'password' => Hash::make(User::DEFAULT_SISWA_PASSWORD),
    ]);

    // 1. Rejects changing to the same default password
    Livewire::actingAs($siswa)
        ->test(Settings::class)
        ->assertSee('Password Anda Masih Default')
        ->set('current_password', User::DEFAULT_SISWA_PASSWORD)
        ->set('password', User::DEFAULT_SISWA_PASSWORD)
        ->set('password_confirmation', User::DEFAULT_SISWA_PASSWORD)
        ->call('updateUserPassword')
        ->assertHasErrors(['password']);

    // 2. Accepts valid new password and clears default password warning
    Livewire::actingAs($siswa)
        ->test(Settings::class)
        ->set('current_password', User::DEFAULT_SISWA_PASSWORD)
        ->set('password', 'PasswordAman2026!')
        ->set('password_confirmation', 'PasswordAman2026!')
        ->call('updateUserPassword')
        ->assertHasNoErrors()
        ->assertSee('Password berhasil diperbarui!')
        ->assertDontSee('Password Anda Masih Default');

    $siswa->refresh();
    expect(Hash::check('PasswordAman2026!', $siswa->password))->toBeTrue();
    expect($siswa->isUsingDefaultPassword())->toBeFalse();
});

test('SiswaRplSeeder seeds all 35 siswa accounts idempotently with default password SKANSABAJAYA321 and removes dummy siswa', function () {
    // Buat akun dummy lama terlebih dahulu untuk memastikan terhapus oleh seeder
    User::create([
        'name' => 'Siswa RPL (Uji Coba)',
        'nisn' => '0061234567',
        'email' => 'siswa@rpl.sch.id',
        'password' => Hash::make('skansabajaya123'),
        'role' => 'siswa',
        'status' => 'active',
    ]);

    $admin = User::create([
        'name' => 'Administrator',
        'email' => 'admin@rpl.sch.id',
        'password' => Hash::make('oldpassword'),
        'role' => 'admin',
        'status' => 'active',
    ]);

    $guru = User::create([
        'name' => 'Guru RPL',
        'email' => 'guru@rpl.sch.id',
        'password' => Hash::make('oldpassword'),
        'role' => 'guru',
        'status' => 'active',
    ]);

    $this->seed(\Database\Seeders\SiswaRplSeeder::class);

    // Pastikan akun dummy 0061234567 sudah terhapus
    expect(User::withTrashed()->where('nisn', '0061234567')->exists())->toBeFalse();
    expect(User::withTrashed()->where('email', 'siswa@rpl.sch.id')->exists())->toBeFalse();

    // Pastikan password Admin & Guru telah diperbarui
    expect(Hash::check('RPLskansabaselalujaya', $admin->fresh()->password))->toBeTrue();
    expect(Hash::check('GURURPLJAYA', $guru->fresh()->password))->toBeTrue();

    expect(User::where('role', 'siswa')->whereIn('nisn', array_column(\Database\Seeders\SiswaRplSeeder::SISWA_LIST, 'nisn'))->count())->toBe(35);

    $alief = User::where('nisn', '18332')->first();
    expect($alief)->not->toBeNull();
    expect($alief->name)->toBe('Alief Saktiawan Prananda');
    expect(Hash::check(User::DEFAULT_SISWA_PASSWORD, $alief->password))->toBeTrue();
    expect($alief->isUsingDefaultPassword())->toBeTrue();

    // Re-running seeder does not duplicate records
    $this->seed(\Database\Seeders\SiswaRplSeeder::class);
    expect(User::where('role', 'siswa')->whereIn('nisn', array_column(\Database\Seeders\SiswaRplSeeder::SISWA_LIST, 'nisn'))->count())->toBe(35);
});

test('inactive user is immediately logged out by role middleware', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'status' => 'inactive',
    ]);

    $this->actingAs($siswa)
        ->get(route('siswa.profil'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

