<?php

use App\Livewire\BeritaDetail;
use App\Livewire\KaryaLikeButton;
use App\Livewire\KaryaSiswaDetail;
use App\Livewire\KaryaSiswaPublic;
use App\Models\Berita;
use App\Models\KaryaLike;
use App\Models\KaryaSiswa;
use App\Models\User;
use Livewire\Livewire;

function makePublishedKaryaForLike(string $judul): KaryaSiswa
{
    $siswa = User::factory()->create(['role' => 'siswa']);

    return KaryaSiswa::create([
        'user_id' => $siswa->id,
        'nama_siswa' => $siswa->name,
        'judul' => $judul,
        'kategori' => 'Web',
        'kelas' => 'XII RPL 1',
        'tahun' => 2026,
        'status' => 'published',
        'published_at' => now(),
        'deskripsi' => 'Deskripsi karya untuk pengujian like.',
    ]);
}

test('user dapat like dan unlike karya siswa', function () {
    $user = User::factory()->create(['role' => 'siswa']);
    $karya = makePublishedKaryaForLike('Karya Like Test');

    Livewire::actingAs($user)
        ->test(KaryaLikeButton::class, ['karyaId' => $karya->id])
        ->call('toggle');

    expect(KaryaLike::where('karya_siswa_id', $karya->id)->where('user_id', $user->id)->exists())->toBeTrue();

    Livewire::actingAs($user)
        ->test(KaryaLikeButton::class, ['karyaId' => $karya->id])
        ->call('toggle');

    expect(KaryaLike::where('karya_siswa_id', $karya->id)->where('user_id', $user->id)->exists())->toBeFalse();
});

test('tamu dapat like karya menggunakan token guest', function () {
    $karya = makePublishedKaryaForLike('Karya Guest Like');

    Livewire::test(KaryaLikeButton::class, ['karyaId' => $karya->id])
        ->call('toggle');

    expect(KaryaLike::where('karya_siswa_id', $karya->id)->whereNotNull('guest_token')->exists())->toBeTrue();
});

test('karya terpopuler diurutkan berdasarkan jumlah like', function () {
    $populer = makePublishedKaryaForLike('Karya Paling Populer');
    $biasa = makePublishedKaryaForLike('Karya Biasa Saja');

    KaryaLike::create([
        'karya_siswa_id' => $populer->id,
        'user_id' => User::factory()->create()->id,
    ]);
    KaryaLike::create([
        'karya_siswa_id' => $populer->id,
        'user_id' => User::factory()->create()->id,
    ]);

    Livewire::test(KaryaSiswaPublic::class)
        ->assertSeeInOrder(['Karya Paling Populer', 'Karya Biasa Saja']);
});

test('like pada halaman detail karya memperbarui jumlah like', function () {
    $user = User::factory()->create(['role' => 'siswa']);
    $karya = makePublishedKaryaForLike('Karya Detail Like');

    Livewire::actingAs($user)
        ->test(KaryaLikeButton::class, ['karyaId' => $karya->id])
        ->call('toggle');

    expect(KaryaLike::where('karya_siswa_id', $karya->id)->where('user_id', $user->id)->exists())->toBeTrue();
});

test('tombol share tampil di detail karya dan berita', function () {
    $karya = makePublishedKaryaForLike('Karya Share Test');

    Livewire::test(KaryaSiswaDetail::class, ['id' => $karya->id])
        ->assertSee('Bagikan')
        ->assertSee('WhatsApp');

    $author = User::factory()->create(['role' => 'admin']);
    $berita = Berita::create([
        'title' => 'Berita Share Test',
        'excerpt' => 'Ringkasan berita untuk pengujian share.',
        'content_md' => 'Isi berita untuk pengujian share.',
        'author_id' => $author->id,
        'published_at' => now(),
        'status' => 'published',
    ]);

    Livewire::test(BeritaDetail::class, ['slug' => $berita->slug])
        ->assertSee('Bagikan')
        ->assertSee('WhatsApp');
});
