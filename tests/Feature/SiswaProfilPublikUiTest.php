<?php

namespace Tests\Feature;

use App\Livewire\Siswa\ProfilPublikEdit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiswaProfilPublikUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_template_magang_dan_developer_dapat_dimuat_ke_editor_readme(): void
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'name' => 'Budi Prakoso',
        ]);

        $this->actingAs($user);

        // Test template magang
        Livewire::test(ProfilPublikEdit::class)
            ->call('loadReadmeTemplate', 'magang')
            ->assertSet('profil_readme', function ($val) {
                return str_contains($val, 'Budi Prakoso') && str_contains($val, 'Tentang Saya');
            })
            ->call('loadReadmeTemplate', 'developer')
            ->assertSet('profil_readme', function ($val) {
                return str_contains($val, 'Budi Prakoso') && str_contains($val, 'Tech Stack Utama');
            });
    }

    public function test_kalkulasi_kelengkapan_profil_bekerja_dengan_benar(): void
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'bio' => null,
            'keahlian' => null,
            'username' => null,
            'profil_readme' => null,
            'photo' => null,
        ]);

        $this->actingAs($user);

        $test = Livewire::test(ProfilPublikEdit::class);
        $completion = $test->get('profileCompletion');
        $this->assertEquals(0, $completion['percentage']);

        // Lengkapi bio dan keahlian
        $user->update([
            'bio' => 'Saya siswa RPL yang antusias.',
            'keahlian' => 'Laravel, Tailwind CSS',
        ]);
        $user->refresh();

        $test = Livewire::test(ProfilPublikEdit::class);
        $completion = $test->get('profileCompletion');
        $this->assertEquals(40, $completion['percentage']); // 2 dari 5 item
    }
}
