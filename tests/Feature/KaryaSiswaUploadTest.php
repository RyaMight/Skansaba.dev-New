<?php

use App\Livewire\KaryaSiswaManager;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('modal structure remains intact with header, body, and footer during cover and bukti uploads', function () {
    Storage::fake('public');

    $siswa = User::factory()->create([
        'role' => 'siswa',
        'nisn' => '1234567890',
        'kelas' => 'XI RPL 1',
    ]);

    $component = Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openCreateModal');

    // Initial modal render: verify Header, Body, and Footer
    $component->assertSee('Buat Karya Baru')
        ->assertSee('1. Cover / Thumbnail Utama Proyek')
        ->assertSee('2. Tangkapan Layar (Screenshots) Aplikasi')
        ->assertSee('3. Bukti Kepemilikan & Orisinalitas Karya')
        ->assertSee('Simpan Perubahan');

    // 1. Upload Cover
    $cover = UploadedFile::fake()->image('cover_project.png', 1280, 720);
    $component->set('cover_image', $cover);

    // Verify after Cover upload: Cover preview card is visible and header/body/footer are intact
    $component->assertSee('Cover Baru Terpilih')
        ->assertSee('Ganti Cover')
        ->assertSee('Buat Karya Baru')
        ->assertSee('Simpan Perubahan');

    // 2. Upload Bukti (PDF & Image)
    $buktiPdf = UploadedFile::fake()->create('laporan-dokumentasi.pdf', 1024, 'application/pdf');
    $buktiImg = UploadedFile::fake()->image('bukti-ide.jpg', 800, 600);
    $component->set('new_bukti_files', [$buktiPdf, $buktiImg]);

    // Verify after Bukti upload: Both files rendered with name and size, and modal structure intact
    $component->assertSee('laporan-dokumentasi.pdf')
        ->assertSee('bukti-ide.jpg')
        ->assertSee('2 Berkas')
        ->assertSee('Buat Karya Baru')
        ->assertSee('Simpan Perubahan');

    // 3. Upload Screenshot
    $shot = UploadedFile::fake()->image('screenshot-dashboard.webp', 1920, 1080);
    $component->set('new_screenshots', [$shot]);

    $component->assertSee('1 / 7 Foto Terisi')
        ->assertSee('Buat Karya Baru')
        ->assertSee('Simpan Perubahan');

    // 4. Test Removal
    $component->call('removeBukti', 0)
        ->assertDontSee('laporan-dokumentasi.pdf')
        ->assertSee('bukti-ide.jpg')
        ->assertSee('1 Berkas');

    $component->call('removeCover')
        ->assertDontSee('Cover Baru Terpilih')
        ->assertSee('Pilih file foto');
});
