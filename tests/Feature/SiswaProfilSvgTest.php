<?php

namespace Tests\Feature;

use App\Livewire\Siswa\ProfilPublikEdit;
use App\Models\ProfilAset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SiswaProfilSvgTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        // Clean up any real files written outside the fake disk
        Storage::disk('public')->deleteDirectory('users');
        Storage::disk('public')->deleteDirectory('tmp');
        parent::tearDown();
    }

    private function createSiswa(array $attrs = []): User
    {
        return User::create(array_merge([
            'name' => 'Siswa Test',
            'email' => 'svg-test@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ], $attrs));
    }

    private function cleanSvg(): UploadedFile
    {
        $svg = <<<'SVG'
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                <rect width="100" height="100" fill="#0284c7"/>
                <text x="50" y="55" text-anchor="middle" fill="#fff" font-size="14">RPL</text>
            </svg>
        SVG;

        return UploadedFile::fake()->createWithContent('info-card.svg', $svg);
    }

    private function maliciousSvg(): UploadedFile
    {
        $svg = <<<'SVG'
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                <script>alert('xss')</script>
                <rect width="100" height="100" fill="#0284c7" onclick="alert('xss')"/>
            </svg>
        SVG;

        return UploadedFile::fake()->createWithContent('evil.svg', $svg);
    }

    public function test_svg_valid_bisa_diupload(): void
    {
        Storage::fake('public');
        $user = $this->createSiswa();
        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('fileSvg', $this->cleanSvg())
            ->call('uploadSvg')
            ->assertHasNoErrors();

        $user->refresh();
        $aset = ProfilAset::where('user_id', $user->id)->first();

        $this->assertNotNull($aset, 'Aset harus tersimpan di database');
        $this->assertStringStartsWith('users/svg/u'.$user->id.'/', $aset->path, 'SVG harus di folder per-akun');
        $this->assertTrue(Storage::disk('public')->exists($aset->path), 'File SVG harus ada di storage');

        // Sanitized content must still be a valid svg root
        $content = Storage::disk('public')->get($aset->path);
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringContainsString('info-card', $aset->nama_file);
    }

    public function test_svg_berbahaya_dibersihkan_saat_upload(): void
    {
        Storage::fake('public');
        $user = $this->createSiswa();
        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('fileSvg', $this->maliciousSvg())
            ->call('uploadSvg')
            ->assertHasNoErrors();

        $user->refresh();
        $aset = ProfilAset::where('user_id', $user->id)->first();
        $this->assertNotNull($aset);

        $content = strtolower(Storage::disk('public')->get($aset->path));
        $this->assertStringNotContainsString('<script', $content, 'Tag script harus dibuang sanitizer');
        $this->assertStringNotContainsString('onclick', $content, 'Event handler harus dibuang sanitizer');
        $this->assertStringNotContainsString('alert(', $content, 'Kode injeksi harus hilang');
    }

    public function test_animasi_smil_tetap_hidup_setelah_sanitasi(): void
    {
        Storage::fake('public');
        $user = $this->createSiswa();
        $this->actingAs($user);

        // SVG mirip hasil generator siswa: clip-path + animate + animateTransform
        $svg = <<<'SVG'
            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
                <clipPath id="r0"><rect x="0" y="0" height="10" width="0"><animate attributeName="width" from="0" to="100" dur="0.5s" fill="freeze"/></rect></clipPath>
                <rect width="100" height="100" fill="#0284c7" clip-path="url(#r0)"/>
                <g opacity="0" transform="translate(0,5)">
                    <text x="10" y="50" fill="#fff">teks</text>
                    <animateTransform attributeName="transform" type="translate" from="0 5" to="0 0" dur="0.4s" fill="freeze" calcMode="spline" keySplines="0.2 0.8 0.2 1"/>
                    <animate attributeName="opacity" from="0" to="1" dur="0.4s" fill="freeze"/>
                </g>
            </svg>
        SVG;

        Livewire::test(ProfilPublikEdit::class)
            ->set('fileSvg', UploadedFile::fake()->createWithContent('animasi.svg', $svg))
            ->call('uploadSvg')
            ->assertHasNoErrors();

        $aset = ProfilAset::where('user_id', $user->id)->first();
        $content = Storage::disk('public')->get($aset->path);

        // Elemen & atribut animasi harus selamat
        $this->assertStringContainsString('<animate', $content, 'Tag animate harus dipertahankan');
        $this->assertStringContainsString('<animateTransform', $content, 'Tag animateTransform harus dipertahankan');
        $this->assertStringContainsString('from=', $content, 'Atribut from harus dipertahankan');
        $this->assertStringContainsString('to=', $content, 'Atribut to harus dipertahankan');
        $this->assertStringContainsString('calcMode', $content, 'Atribut calcMode harus dipertahankan');
        $this->assertStringContainsString('keySplines', $content, 'Atribut keySplines harus dipertahankan');
    }

    public function test_animasi_yang_menyerang_href_dibuang(): void
    {
        Storage::fake('public');
        $user = $this->createSiswa();
        $this->actingAs($user);

        // SVG yang mencoba mengubah href sebuah link menjadi javascript: lewat animasi
        $svg = <<<'SVG'
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100">
                <a href="https://example.com"><rect width="100" height="100"/></a>
                <animate attributeName="href" to="javascript:alert(1)" dur="1s" fill="freeze"/>
                <set attributeName="xlink:href" to="javascript:alert(2)"/>
            </svg>
        SVG;

        Livewire::test(ProfilPublikEdit::class)
            ->set('fileSvg', UploadedFile::fake()->createWithContent('evil-anim.svg', $svg))
            ->call('uploadSvg')
            ->assertHasNoErrors();

        $aset = ProfilAset::where('user_id', $user->id)->first();
        $content = strtolower(Storage::disk('public')->get($aset->path));

        $this->assertStringNotContainsString('javascript:', $content, 'Animasi href ke javascript: harus dibuang');
    }

    public function test_file_bukan_svg_ditolak(): void
    {
        Storage::fake('public');
        $user = $this->createSiswa();
        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('fileSvg', UploadedFile::fake()->createWithContent('berkas.txt', 'bukan svg'))
            ->call('uploadSvg')
            ->assertHasErrors(['fileSvg']);

        $this->assertSame(0, ProfilAset::where('user_id', $user->id)->count());
    }

    public function test_kuota_maksimal_per_akun(): void
    {
        Storage::fake('public');
        $user = $this->createSiswa();
        $this->actingAs($user);

        // Isi kuota langsung lewat DB
        for ($i = 0; $i < 5; $i++) {
            ProfilAset::create([
                'user_id' => $user->id,
                'nama_file' => "aset-$i.svg",
                'path' => "users/svg/u{$user->id}/aset-$i.svg",
                'size' => 100,
            ]);
        }

        Livewire::test(ProfilPublikEdit::class)
            ->set('fileSvg', $this->cleanSvg())
            ->call('uploadSvg')
            ->assertHasErrors(['fileSvg']);

        $this->assertSame(5, ProfilAset::where('user_id', $user->id)->count());
    }

    public function test_hapus_aset_menghapus_file_dan_row(): void
    {
        Storage::fake('public');
        $user = $this->createSiswa();
        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('fileSvg', $this->cleanSvg())
            ->call('uploadSvg');

        $aset = ProfilAset::where('user_id', $user->id)->first();
        $this->assertNotNull($aset);
        $this->assertTrue(Storage::disk('public')->exists($aset->path));

        Livewire::test(ProfilPublikEdit::class)
            ->call('deleteSvg', $aset->id)
            ->assertHasNoErrors();

        $this->assertNull(ProfilAset::find($aset->id), 'Row aset harus terhapus');
        $this->assertFalse(Storage::disk('public')->exists($aset->path), 'File aset harus terhapus');
    }

    public function test_svg_tampil_di_profil_publik_lewat_readme(): void
    {
        Storage::fake('public');
        $user = $this->createSiswa([
            'username' => 'siswa-svg',
            'profil_readme' => null,
        ]);
        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('fileSvg', $this->cleanSvg())
            ->call('uploadSvg')
            ->assertHasNoErrors();

        $aset = ProfilAset::where('user_id', $user->id)->first();

        // Sisipkan snippet ke README (seperti yang dilakukan siswa)
        $user->update(['profil_readme' => "## Halo\n\n".$aset->snippet."\n"]);

        $response = $this->get('/profil/siswa-svg');
        $response->assertOk();
        $response->assertSee('storage/users/svg/u'.$user->id, false);
    }
}
