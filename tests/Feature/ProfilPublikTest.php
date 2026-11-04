<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilPublikTest extends TestCase
{
    use RefreshDatabase;

    private function makeSiswa(array $attrs = []): User
    {
        return User::create(array_merge([
            'name' => 'Siswa Test',
            'email' => 'test' . rand(1, 99999) . '@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ], $attrs));
    }

    public function test_profil_publik_bisa_diakses_tanpa_login_via_id(): void
    {
        $siswa = $this->makeSiswa();

        $this->get('/profil/' . $siswa->id)
            ->assertOk()
            ->assertSee($siswa->name);
    }

    public function test_profil_publik_bisa_diakses_via_username_kustom(): void
    {
        $siswa = $this->makeSiswa(['username' => 'budi-dev']);

        $this->get('/profil/budi-dev')
            ->assertOk()
            ->assertSee('budi-dev');
    }

    public function test_profil_publik_404_untuk_username_tak_dikenal(): void
    {
        $this->get('/profil/tidak-ada-xyz')->assertNotFound();
    }

    public function test_readme_dirender_dan_skript_dibersihkan(): void
    {
        $siswa = $this->makeSiswa([
            // Script di baris terpisah supaya paragraf tetap ter-render setelah script di-strip
            'profil_readme' => "# Halo\n\n<script>alert('xss')</script>\n\nParagraf **aman**.",
        ]);

        $response = $this->get('/profil/' . $siswa->id);
        $response->assertOk();

        $content = $response->getContent();
        // Script harus dibersihkan total (cek di area konten README, bukan script framework halaman)
        $readmeArea = substr($content, 0, strpos($content, '</main>') ?: strlen($content));
        $this->assertStringNotContainsString('alert(', $readmeArea);
        // Readme dirender sebagai HTML (heading h1 ada)
        $this->assertStringContainsString('<h1', $readmeArea);
        // Paragraf tetap ter-render dengan bold
        $this->assertStringContainsString('<strong>aman</strong>', $readmeArea);
    }

    public function test_readme_mendukung_html_gaya_github_dengan_aman(): void
    {
        $siswa = $this->makeSiswa([
            'profil_readme' => '<p align="center"><a href="https://contoh.dev"><img src="https://svg.contoh.com?font=Fira" alt="typing" /></a></p>' . "\n\n" . "Paragraf **tebal**.",
        ]);

        $content = $this->get('/profil/' . $siswa->id)->getContent();

        // Raw HTML ala GitHub dipertahankan
        $this->assertStringContainsString('<p align="center">', $content);
        $this->assertStringContainsString('svg.contoh.com', $content);
        // Markdown tetap ter-render
        $this->assertStringContainsString('<strong>tebal</strong>', $content);
    }

    public function test_readme_memblokir_script_dan_javascript_src(): void
    {
        $siswa = $this->makeSiswa([
            'profil_readme' => "<script>alert('xss')</script>\n\n<img src=\"javascript:alert(1)\" alt=\"bad\">",
        ]);

        $content = $this->get('/profil/' . $siswa->id)->getContent();

        // Sumber gambar berbahaya dinetralkan
        $this->assertStringNotContainsString('javascript:alert(1)', $content);
        // Tidak ada payload script yang lolos di area konten
        $this->assertStringNotContainsString('alert(', substr($content, 0, strpos($content, '</main>') ?: strlen($content)));
    }

    public function test_akun_non_siswa_tidak_bisa_diakses(): void
    {
        $admin = $this->makeSiswa(['role' => 'admin']);

        $this->get('/profil/' . $admin->id)->assertNotFound();
    }
}
