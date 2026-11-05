<?php

use App\Livewire\KaryaSiswaManager;
use App\Models\KaryaSiswa;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('siswa can upload up to 7 screenshots with visual gallery slots', function () {
    Storage::fake('public');

    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
    ]);

    $shot1 = UploadedFile::fake()->image('ss1.png', 800, 600);
    $shot2 = UploadedFile::fake()->image('ss2.png', 800, 600);

    $test = Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openCreateModal')
        ->set('new_screenshots', [$shot1, $shot2])
        ->assertSee('Foto 1')
        ->assertSee('Foto 2')
        ->assertSee('2 / 7 Foto Terisi')
        ->assertSee('Tambah Foto');

    // Add 5 more screenshots up to 7
    $moreShots = [];
    for ($i = 3; $i <= 7; $i++) {
        $moreShots[] = UploadedFile::fake()->image("ss{$i}.png", 800, 600);
    }

    $test->set('new_screenshots', $moreShots)
        ->assertSee('7 / 7 Foto Terisi')
        ->assertSee('Semua 7 slot foto telah terisi lengkap')
        ->assertDontSeeHtml('+ Tambah Foto');
});

test('siswa can see buat karya button in karya saya and open create modal', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Ahmad Faiz',
        'kelas' => 'XII RPL 2',
    ]);

    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->assertSee('Karya Saya')
        ->assertSeeHtml('Buat Karya')
        ->call('openCreateModal')
        ->assertSet('showCreateModal', true)
        ->call('closeCreateModal')
        ->assertSet('showCreateModal', false);
});

test('siswa can create and submit new karya for verification', function () {
    Storage::fake('public');

    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Ahmad Faiz',
        'kelas' => 'XII RPL 2',
    ]);

    $cover = UploadedFile::fake()->image('cover-proyek.png', 800, 600);

    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openCreateModal')
        ->set('judul', 'Sistem Otomasi IoT Pertanian')
        ->set('kategori', 'iot')
        ->set('deskripsi_singkat', 'Proyek kendali irigasi otomatis berbasis Arduino dan ESP32.')
        ->set('deskripsi', 'Penjelasan lengkap arsitektur sensor suhu, kelembaban, dan pompa air otomatis.')
        ->set('teknologi', 'C++, ESP32, MQTT, Laravel')
        ->set('cover_image', $cover)
        ->set('url_repo', 'https://github.com/ahmadfaiz/iot-pertanian')
        ->call('save', 'review')
        ->assertHasNoErrors()
        ->assertSet('showCreateModal', false);

    $karya = KaryaSiswa::where('judul', 'Sistem Otomasi IoT Pertanian')->first();
    expect($karya)->not->toBeNull()
        ->and($karya->user_id)->toBe($siswa->id)
        ->and($karya->nama_siswa)->toBe('Ahmad Faiz')
        ->and($karya->kelas)->toBe('XII RPL 2')
        ->and($karya->status)->toBe('review')
        ->and($karya->kategori)->toBe('iot')
        ->and($karya->teknologi)->toContain('ESP32');
});

test('siswa can create karya with cover, max 3 screenshots, and proof of work, save as draft, then submit draft', function () {
    Storage::fake('public');

    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
        'kelas' => 'XI RPL 1',
    ]);

    $cover = UploadedFile::fake()->image('cover-proyek.png', 800, 600);
    $screenshot1 = UploadedFile::fake()->image('ss1.png', 800, 600);
    $screenshot2 = UploadedFile::fake()->image('ss2.png', 800, 600);
    $screenshot3 = UploadedFile::fake()->image('ss3.png', 800, 600);
    $buktiDoc = UploadedFile::fake()->create('bukti-orisinalitas.pdf', 100, 'application/pdf');

    // 1. Siswa creates karya as draft
    $component = Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openCreateModal')
        ->set('judul', 'Aplikasi Manajemen Kas Digital')
        ->set('kategori', 'web')
        ->set('deskripsi_singkat', 'Aplikasi pembukuan kas kelas berbasis web.')
        ->set('deskripsi', 'Sistem ini memfasilitasi bendahara kelas mencatat pemasukan dan pengeluaran secara transparan.')
        ->set('teknologi', 'Laravel, Livewire, Tailwind CSS')
        ->set('cover_image', $cover)
        ->set('screenshots', [$screenshot1, $screenshot2, $screenshot3])
        ->set('bukti_files', [$buktiDoc])
        ->call('save', 'draft')
        ->assertHasNoErrors()
        ->assertSet('showCreateModal', false);

    $karya = KaryaSiswa::where('judul', 'Aplikasi Manajemen Kas Digital')->first();
    expect($karya)->not->toBeNull()
        ->and($karya->status)->toBe('draft')
        ->and($karya->user_id)->toBe($siswa->id)
        ->and($karya->gambar)->not->toBeNull()
        ->and(count($karya->screenshots))->toBe(3)
        ->and(count($karya->bukti))->toBe(1);

    // 2. Card shows "Draft Siswa" and "Ajukan Verifikasi" button
    $component->assertSee('Aplikasi Manajemen Kas Digital')
        ->assertSee('Draft Siswa')
        ->assertSee('Ajukan Verifikasi');

    // 3. Siswa submits the draft using submitDraft
    $component->call('submitDraft', $karya->id)
        ->assertSee('berhasil diajukan untuk verifikasi');

    $karya->refresh();
    expect($karya->status)->toBe('review');
});

test('screenshots limit is enforced at max 7 files', function () {
    Storage::fake('public');

    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
    ]);

    $shots = [];
    for ($i = 1; $i <= 8; $i++) {
        $shots[] = UploadedFile::fake()->image("s{$i}.png");
    }

    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openCreateModal')
        ->set('judul', 'Proyek Melebihi Batas Screenshot')
        ->set('kategori', 'web')
        ->set('deskripsi', 'Deskripsi pengujian batas screenshot aplikasi karya siswa.')
        ->set('screenshots', $shots)
        ->call('save', 'review')
        ->assertHasErrors(['screenshots']);
});

test('siswa can edit existing karya, remove screenshot/cover, and save updates', function () {
    Storage::fake('public');

    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
    ]);

    $karya = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Judul Awal Karya',
        'kategori' => 'web',
        'deskripsi' => 'Deskripsi awal yang cukup panjang untuk memenuhi batas minimal karakter validasi.',
        'teknologi' => ['PHP', 'MySQL'],
        'gambar' => 'karya-siswa/dummy-cover.webp',
        'screenshots' => ['karya-screenshots/s1.webp', 'karya-screenshots/s2.webp'],
        'bukti' => ['karya-bukti/doc.pdf'],
        'status' => 'draft',
    ]);

    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openEditModal', $karya->id)
        ->assertSet('showCreateModal', true)
        ->assertSet('editingKaryaId', $karya->id)
        ->assertSet('judul', 'Judul Awal Karya')
        ->assertSet('existing_cover', 'karya-siswa/dummy-cover.webp')
        ->assertCount('existing_screenshots', 2)
        ->assertCount('existing_bukti', 1)
        ->call('removeExistingScreenshot', 0)
        ->assertCount('existing_screenshots', 1)
        ->set('judul', 'Judul Karya Yang Telah Diperbarui')
        ->call('save', 'draft')
        ->assertHasNoErrors()
        ->assertSet('showCreateModal', false);

    $karya->refresh();
    expect($karya->judul)->toBe('Judul Karya Yang Telah Diperbarui')
        ->and(count($karya->screenshots))->toBe(1);
});

test('siswa cannot submit or edit karya belonging to another student', function () {
    $siswa1 = User::factory()->create(['role' => 'siswa', 'name' => 'Siswa Pertama']);
    $siswa2 = User::factory()->create(['role' => 'siswa', 'name' => 'Siswa Kedua']);

    $karya = KaryaSiswa::create([
        'user_id' => $siswa1->id,
        'nama_siswa' => $siswa1->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Karya Milik Siswa Pertama',
        'kategori' => 'web',
        'deskripsi' => 'Deskripsi karya siswa pertama yang tidak boleh diubah siswa lain.',
        'status' => 'draft',
    ]);

    // Siswa 2 tries to submit Siswa 1's draft
    Livewire::actingAs($siswa2)
        ->test(KaryaSiswaManager::class)
        ->call('submitDraft', $karya->id)
        ->assertSee('tidak memiliki hak akses');

    $karya->refresh();
    expect($karya->status)->toBe('draft');

    // Siswa 2 tries to open edit modal for Siswa 1's karya
    Livewire::actingAs($siswa2)
        ->test(KaryaSiswaManager::class)
        ->call('openEditModal', $karya->id)
        ->assertSee('tidak memiliki hak akses')
        ->assertSet('showCreateModal', false);
});

test('siswa cannot edit or delete karya when status is review', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
    ]);

    $karya = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Sistem Reservasi Lab RPL',
        'kategori' => 'web',
        'deskripsi' => 'Aplikasi web reservasi lab komputer untuk pembelajaran praktik.',
        'status' => 'review',
    ]);

    $test = Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        // Verify card UI shows locked status and does not render Edit / Hapus buttons
        ->assertSee('Sistem Reservasi Lab RPL')
        ->assertSee('Terkunci (Sedang Ditinjau)')
        ->assertDontSeeHtml('title="Edit draf karya"')
        ->assertDontSeeHtml('title="Hapus karya ini"');

    // Attempting to open edit modal directly is blocked
    $test->call('openEditModal', $karya->id)
        ->assertSee('tidak dapat diedit karena sudah diajukan')
        ->assertSet('showCreateModal', false);

    // Attempting to confirm delete directly is blocked
    $test->call('confirmDelete', $karya->id)
        ->assertSee('tidak dapat dihapus karena sudah diajukan')
        ->assertSet('confirmingDeleteId', null);

    // Karya must still exist in DB
    expect(KaryaSiswa::find($karya->id))->not->toBeNull();

    // Opening detail modal does not display Edit or Delete buttons for student
    $test->call('openDetailModal', $karya->id)
        ->assertSet('showDetailModal', true)
        ->assertSee('Terkunci dari perubahan selama proses verifikasi')
        ->assertDontSeeHtml('Edit Karya')
        ->assertDontSeeHtml('Hapus Karya Ini');
});

test('create and edit modal renders simpan perubahan button', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
    ]);

    $karya = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Karya Draf Awal',
        'kategori' => 'web',
        'deskripsi' => 'Deskripsi untuk pengujian tombol simpan perubahan.',
        'status' => 'draft',
    ]);

    // 1. Create mode modal shows "Simpan Perubahan"
    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openCreateModal')
        ->assertSet('showCreateModal', true)
        ->assertSee('Simpan Perubahan')
        ->assertSee('Simpan Draft');

    // 2. Edit mode modal shows "Simpan Perubahan"
    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openEditModal', $karya->id)
        ->assertSet('showCreateModal', true)
        ->assertSet('editingKaryaId', $karya->id)
        ->assertSee('Simpan Perubahan')
        ->assertSee('Simpan Sebagai Draft');
});

test('siswa with multiple karya renders each card independently in grid without nesting collision', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
    ]);

    $karya1 = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Proyek Pertama Siswa',
        'kategori' => 'web',
        'deskripsi' => 'Deskripsi proyek pertama siswa RPL untuk pengujian layout kartu ganda.',
        'status' => 'draft',
    ]);

    $karya2 = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Proyek Kedua Siswa',
        'kategori' => 'mobile',
        'deskripsi' => 'Deskripsi proyek kedua siswa RPL untuk memastikan tidak ada tabrakan markup kartu.',
        'status' => 'review',
    ]);

    $karya3 = KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'kelas' => 'XI RPL 1',
        'tahun' => 2026,
        'judul' => 'Proyek Ketiga Siswa',
        'kategori' => 'game',
        'deskripsi' => 'Deskripsi proyek ketiga siswa RPL untuk memastikan grid bersih dan rapi.',
        'status' => 'published',
    ]);

    $test = Livewire::actingAs($siswa)->test(KaryaSiswaManager::class);

    $test->assertSee('Proyek Pertama Siswa')
        ->assertSee('Proyek Kedua Siswa')
        ->assertSee('Proyek Ketiga Siswa');

    // Inspect rendered HTML: Ensure each card is properly closed and not nested inside another
    $html = $test->html();

    // Verify grid exists
    expect($html)->toContain('grid grid-cols-1 md:grid-cols-2 gap-6');

    // Count card root occurrences and verify they are sibling elements, not nested
    $cardClass = 'shadow-2xs overflow-hidden flex flex-col justify-between hover:border-slate-300';
    $cardMatches = substr_count($html, $cardClass);
    expect($cardMatches)->toBe(3);
});

test('siswa can upload up to 7 screenshots and save successfully', function () {
    Storage::fake('public');

    $siswa = User::factory()->create([
        'role' => 'siswa',
        'name' => 'Kalis Lintang Pradipta',
    ]);

    $shots = [];
    for ($i = 1; $i <= 7; $i++) {
        $shots[] = UploadedFile::fake()->image("ss{$i}.png", 800, 600);
    }

    Livewire::actingAs($siswa)
        ->test(KaryaSiswaManager::class)
        ->call('openCreateModal')
        ->set('judul', 'Proyek Lengkap Tujuh Screenshot')
        ->set('kategori', 'web')
        ->set('deskripsi', 'Pengujian fitur unggah 7 tangkapan layar antarmuka proyek siswa.')
        ->set('screenshots', $shots)
        ->call('save', 'draft')
        ->assertHasNoErrors()
        ->assertSet('showCreateModal', false);

    $karya = KaryaSiswa::where('judul', 'Proyek Lengkap Tujuh Screenshot')->first();
    expect($karya)->not->toBeNull()
        ->and(count($karya->screenshots))->toBe(7);
});
