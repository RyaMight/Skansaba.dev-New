<?php

namespace Tests\Feature;

use App\Livewire\KaryaSiswaDetail;
use App\Livewire\Siswa\Profil;
use App\Livewire\Siswa\ProfilDashboard;
use App\Livewire\Siswa\ProfilPublikEdit;
use App\Models\KaryaSiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiswaKeahlianProfileTest extends TestCase
{
    use RefreshDatabase;

    private function makeSiswa(array $attrs = []): User
    {
        return User::create(array_merge([
            'name' => 'Ahmad Syahputra',
            'email' => 'ahmad' . rand(1, 99999) . '@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
            'kelas' => 'XII RPL 1',
            'keahlian' => 'Laravel, Tailwind CSS, Vue.js',
        ], $attrs));
    }

    public function test_keahlian_terlihat_di_profil_siswa_dan_bisa_diedit(): void
    {
        $siswa = $this->makeSiswa();

        $this->actingAs($siswa);

        Livewire::test(Profil::class)
            ->assertSee('Tech Stack:')
            ->assertSee('Laravel')
            ->assertSee('Tailwind CSS')
            ->assertSee('Vue.js')
            ->set('keahlian', 'Flutter, Dart, Firebase')
            ->call('saveProfile')
            ->assertHasNoErrors();

        $this->assertEquals('Flutter, Dart, Firebase', $siswa->fresh()->keahlian);
        $this->assertEquals(['Flutter', 'Dart', 'Firebase'], $siswa->fresh()->keahlian_list);
    }

    public function test_keahlian_terlihat_di_profil_dashboard(): void
    {
        $siswa = $this->makeSiswa();

        $this->actingAs($siswa);

        Livewire::test(ProfilDashboard::class)
            ->assertSee('Tech Stack:')
            ->assertSee('Laravel')
            ->assertSee('Tailwind CSS')
            ->assertSee('Vue.js')
            ->assertSee('Keahlian & Tech Stack');
    }

    public function test_keahlian_terlihat_di_profil_publik_edit_header(): void
    {
        $siswa = $this->makeSiswa();

        $this->actingAs($siswa);

        Livewire::test(ProfilPublikEdit::class)
            ->assertSee('Tech Stack:')
            ->assertSee('Laravel')
            ->assertSee('Tailwind CSS');
    }

    public function test_keahlian_terlihat_di_profil_publik(): void
    {
        $siswa = $this->makeSiswa(['username' => 'ahmad-syah']);

        $this->get('/profil/ahmad-syah')
            ->assertOk()
            ->assertSee('Keahlian & Tech Stack')
            ->assertSee('Laravel')
            ->assertSee('Tailwind CSS')
            ->assertSee('Vue.js');
    }

    public function test_keahlian_penulis_terlihat_di_detail_karya_siswa(): void
    {
        $siswa = $this->makeSiswa(['keahlian' => 'Laravel, PostgreSQL']);

        $karya = KaryaSiswa::create([
            'user_id' => $siswa->id,
            'judul' => 'Aplikasi Perpustakaan Sekolah',
            'deskripsi' => 'Aplikasi web untuk manajemen sirkulasi buku.',
            'kategori' => 'Web App',
            'teknologi' => ['Laravel', 'PostgreSQL'],
            'nama_siswa' => $siswa->name,
            'kelas' => $siswa->kelas,
            'tahun' => 2026,
            'status' => 'published',
            'published_at' => now(),
        ]);

        Livewire::test(KaryaSiswaDetail::class, ['id' => $karya->id])
            ->assertSee($siswa->name)
            ->assertSee('Penulis & Pembuat Karya')
            ->assertSee('Laravel')
            ->assertSee('PostgreSQL');
    }
}
