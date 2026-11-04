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
}
