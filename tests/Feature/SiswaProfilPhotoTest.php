<?php

namespace Tests\Feature;

use App\Livewire\Siswa\Profil;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SiswaProfilPhotoTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        // Clean up any real files written outside the fake disk
        Storage::disk('public')->deleteDirectory('users');
        parent::tearDown();
    }

    private function tinyBase64Png(): string
    {
        // 1x1 transparent PNG
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';
    }

    public function test_foto_profil_tersimpan_permanen_ke_database(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Siswa Test',
            'email' => 'test@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        Livewire::test(Profil::class)
            ->set('name', 'Siswa Test')
            ->set('email', 'test@rpl.sch.id')
            ->set('photoBase64', $this->tinyBase64Png())
            ->call('saveProfile')
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertNotNull($user->photo, 'Kolom photo harus terisi setelah upload');
        $this->assertTrue(
            Storage::disk('public')->exists($user->photo),
            'File foto harus ada di storage'
        );
    }

    public function test_foto_lama_kehapus_ketika_ganti_foto_baru(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Siswa Test',
            'email' => 'test@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        // Upload pertama
        Livewire::test(Profil::class)
            ->set('photoBase64', $this->tinyBase64Png())
            ->call('saveProfile');

        $user->refresh();
        $firstPhoto = $user->photo;
        $this->assertNotNull($firstPhoto);
        $this->assertTrue(Storage::disk('public')->exists($firstPhoto));

        // Upload kedua (ganti foto)
        Livewire::test(Profil::class, ['user' => $user])
            ->set('photoBase64', $this->tinyBase64Png())
            ->call('saveProfile');

        $user->refresh();
        $secondPhoto = $user->photo;

        $this->assertNotSame($firstPhoto, $secondPhoto, 'Path foto harus berubah');
        $this->assertTrue(Storage::disk('public')->exists($secondPhoto), 'Foto baru harus ada');
        $this->assertFalse(Storage::disk('public')->exists($firstPhoto), 'Foto lama harus terhapus');
    }

    public function test_isolasi_folder_per_akun(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Siswa Test',
            'email' => 'test@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        Livewire::test(Profil::class)
            ->set('photoBase64', $this->tinyBase64Png())
            ->call('saveProfile');

        $user->refresh();

        $this->assertStringStartsWith(
            'users/photos/u' . $user->id . '/',
            $user->photo,
            'Foto harus tersimpan di folder per-akun'
        );
    }

    public function test_modal_edit_foto_menampilkan_fitur_frame_dan_geser(): void
    {
        $user = User::create([
            'name' => 'Dani Siswa',
            'email' => 'dani@rpl.sch.id',
            'username' => 'danisiswa',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        // Test modal di ProfilPublikEdit
        $comp = Livewire::test(\App\Livewire\Siswa\ProfilPublikEdit::class)
            ->call('openEditModal')
            ->assertSee('Perbarui Foto Profil')
            ->assertSee('Pilih file foto')
            ->assertSee('Geser Foto (Kanan - Kiri)')
            ->assertSee('Bentuk Bingkai Foto Profil')
            ->assertSee('Gambaran Foto Profil')
            ->assertSee('Tampilan di Halaman Profil')
            ->assertSee('Tampilan di Bilah Menu');

        $html = $comp->html();
        $this->assertMatchesRegularExpression('/x-data="(\{\s*croppie:\s*null,.*?\})"\s*@croppie-update/s', $html);
        if (preg_match('/x-data="(\{\s*croppie:\s*null,.*?\})"\s*@croppie-update/s', $html, $matches)) {
            $this->assertStringNotContainsString('"', $matches[1], 'x-data tidak boleh mengandung tanda kutip ganda (") di dalamnya agar HTML attribute tidak pecah');
        }

        // Test modal di Profil
        Livewire::test(\App\Livewire\Siswa\Profil::class)
            ->call('openEditModal')
            ->assertSee('Edit Profil Siswa')
            ->assertSee('Geser Foto (Kanan - Kiri)')
            ->assertSee('Bentuk Bingkai Foto Profil')
            ->assertSee('Gambaran Foto Profil');

        // Test jika user sudah punya foto, muncul tombol "Edit Ulang Posisi" di samping "Ganti Foto"
        Storage::fake('public');
        $photoPath = 'users/photos/u'.$user->id.'/avatar.webp';
        Storage::disk('public')->put($photoPath, 'dummy-photo-content');
        $user->update(['photo' => $photoPath]);
        $this->actingAs($user->fresh());
        Livewire::test(\App\Livewire\Siswa\ProfilPublikEdit::class)
            ->call('openEditModal')
            ->assertSee('Edit Ulang Posisi')
            ->assertSee('Ganti Foto');
    }

    public function test_save_photo_langsung_dengan_argumen_base64_di_profil_publik_edit(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Siswa Publik Test',
            'email' => 'publik@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        Livewire::test(\App\Livewire\Siswa\ProfilPublikEdit::class)
            ->call('savePhoto', $this->tinyBase64Png())
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertNotNull($user->photo, 'Foto profil harus tersimpan di database');
        $this->assertTrue(Storage::disk('public')->exists($user->photo));
    }

    public function test_save_profile_langsung_dengan_argumen_base64_di_profil_siswa(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Siswa Akun Test',
            'email' => 'akun@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        Livewire::test(Profil::class)
            ->call('saveProfile', $this->tinyBase64Png())
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertNotNull($user->photo, 'Foto profil harus tersimpan di database');
        $this->assertTrue(Storage::disk('public')->exists($user->photo));
    }

    public function test_photo_url_returns_null_when_file_does_not_exist(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Siswa Missing File',
            'email' => 'missing@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
            'photo' => 'users/photos/u999/non_existent.webp',
        ]);

        // File belum pernah dibuat di disk fake
        $this->assertNull(
            $user->photo_url,
            'photo_url harus return null jika file fisik tidak ada di disk (mencegah broken 404 image)'
        );

        // Ketika file dibuat di storage
        Storage::disk('public')->put($user->photo, 'fake-content');
        $user->refresh();

        $this->assertNotNull(
            $user->photo_url,
            'photo_url harus return URL valid ketika file fisik ada di disk'
        );
    }

    public function test_sidebar_menu_menampilkan_foto_profil_dan_sinkron_saat_diperbarui(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Siswa RPL (Uji Coba)',
            'email' => 'siswa.sidebar@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $this->actingAs($user);

        // Sebelum ada foto, sidebar menampilkan inisial "SR"
        $sidebar = Livewire::test(\App\Livewire\Components\SidebarMenu::class);
        $sidebar->assertSee('SR');

        // Saat foto disimpan di ProfilPublikEdit, event profile-photo-updated di-dispatch
        Livewire::test(\App\Livewire\Siswa\ProfilPublikEdit::class)
            ->call('savePhoto', $this->tinyBase64Png())
            ->assertDispatched('profile-photo-updated');

        // Sidebar merespons event profile-photo-updated dan merender <img> photo_url terbaru
        $user->refresh();
        $sidebar->dispatch('profile-photo-updated')
            ->assertSee($user->photo_url, false);
    }
}
