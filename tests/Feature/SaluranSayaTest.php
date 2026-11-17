<?php

use App\Livewire\Siswa\SaluranSaya;
use App\Models\User;
use Livewire\Livewire;

test('siswa dapat menyimpan website portofolio dan tampil di profil publik', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'username' => 'portofolio-siswa',
    ]);

    Livewire::actingAs($siswa)
        ->test(SaluranSaya::class)
        ->set('saluran.website.url', 'portofolio-siswa.com')
        ->set('saluran.website.active', true)
        ->call('save')
        ->assertHasNoErrors();

    $siswa->refresh();

    expect($siswa->saluran_kontak['website']['url'])->toBe('https://portofolio-siswa.com');
    expect($siswa->saluran_kontak['website']['active'])->toBeTrue();

    $this->get(route('profil.public', $siswa->username))
        ->assertOk()
        ->assertSee('Website')
        ->assertSee('portofolio-siswa.com');
});

test('website portofolio tidak tampil jika toggle dimatikan', function () {
    $siswa = User::factory()->create([
        'role' => 'siswa',
        'username' => 'portofolio-off',
        'saluran_kontak' => [
            'website' => [
                'url' => 'https://portofolio-off.com',
                'active' => false,
            ],
        ],
    ]);

    $this->get(route('profil.public', $siswa->username))
        ->assertOk()
        ->assertDontSee('portofolio-off.com');
});
