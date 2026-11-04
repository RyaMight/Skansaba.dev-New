<?php

namespace Tests\Feature;

use App\Livewire\Siswa\ProfilPublikEdit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsernameCooldownTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_bisa_ganti_username_pertama_kali_dengan_popup_konfirmasi(): void
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'username' => null,
            'username_changed_at' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('username', 'ahmad-baru')
            ->call('saveBiodata')
            ->assertSet('showUsernameConfirmModal', true)
            ->assertHasNoErrors()
            ->call('confirmSaveBiodata')
            ->assertSet('showUsernameConfirmModal', false)
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals('ahmad-baru', $user->username);
        $this->assertNotNull($user->username_changed_at);
        $this->assertFalse($user->canChangeUsername());
    }

    public function test_user_tidak_bisa_ganti_username_lagi_dalam_7_hari(): void
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'username' => 'ahmad-lama',
            'username_changed_at' => now()->subDays(2),
        ]);

        $this->actingAs($user);
        $this->assertFalse($user->canChangeUsername());
        $this->assertEquals(5, $user->daysUntilNextUsernameChange());

        Livewire::test(ProfilPublikEdit::class)
            ->set('username', 'ahmad-coba-ganti')
            ->call('saveBiodata')
            ->assertHasErrors(['username'])
            ->assertSet('showUsernameConfirmModal', false);

        $user->refresh();
        $this->assertEquals('ahmad-lama', $user->username);
    }

    public function test_user_bisa_ganti_username_setelah_7_hari(): void
    {
        $user = User::factory()->create([
            'role' => 'siswa',
            'username' => 'ahmad-lama',
            'username_changed_at' => now()->subDays(8),
        ]);

        $this->actingAs($user);
        $this->assertTrue($user->canChangeUsername());

        Livewire::test(ProfilPublikEdit::class)
            ->set('username', 'ahmad-baru-lolos')
            ->call('saveBiodata')
            ->assertSet('showUsernameConfirmModal', true)
            ->call('confirmSaveBiodata')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals('ahmad-baru-lolos', $user->username);
        $this->assertTrue($user->username_changed_at->isToday());
    }

    public function test_popup_dan_cooldown_berlaku_setiap_kali_ganti_username_berulang_kali(): void
    {
        // Ganti pertama (dari null ke username-1)
        $user = User::factory()->create([
            'role' => 'siswa',
            'username' => null,
            'username_changed_at' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('username', 'username-1')
            ->call('saveBiodata')
            ->assertSet('showUsernameConfirmModal', true)
            ->call('confirmSaveBiodata')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals('username-1', $user->username);
        $this->assertFalse($user->canChangeUsername());

        // Simulasi 8 hari kemudian: ganti kedua (dari username-1 ke username-2)
        $user->update(['username_changed_at' => now()->subDays(8)]);
        $user->refresh();
        $this->assertTrue($user->canChangeUsername());

        Livewire::test(ProfilPublikEdit::class)
            ->set('username', 'username-2')
            ->call('saveBiodata')
            ->assertSet('showUsernameConfirmModal', true) // Popup konfirmasi tetap muncul!
            ->call('confirmSaveBiodata')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals('username-2', $user->username);
        $this->assertFalse($user->canChangeUsername()); // Cooldown 7 hari aktif lagi!

        // Coba ganti lagi saat cooldown masih 3 hari: ditolak
        $user->update(['username_changed_at' => now()->subDays(3)]);
        $user->refresh();
        $this->assertFalse($user->canChangeUsername());

        Livewire::test(ProfilPublikEdit::class)
            ->set('username', 'username-3')
            ->call('saveBiodata')
            ->assertHasErrors(['username'])
            ->assertSet('showUsernameConfirmModal', false);

        // Simulasi 8 hari lagi: ganti ketiga (dari username-2 ke username-3)
        $user->update(['username_changed_at' => now()->subDays(8)]);
        $user->refresh();
        $this->assertTrue($user->canChangeUsername());

        Livewire::test(ProfilPublikEdit::class)
            ->set('username', 'username-3')
            ->call('saveBiodata')
            ->assertSet('showUsernameConfirmModal', true) // Popup konfirmasi tetap muncul lagi!
            ->call('confirmSaveBiodata')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals('username-3', $user->username);
        $this->assertFalse($user->canChangeUsername()); // Cooldown 7 hari aktif lagi!
    }

    public function test_username_tidak_boleh_sama_dengan_user_lain(): void
    {
        User::factory()->create([
            'username' => 'budi-developer',
        ]);

        $user = User::factory()->create([
            'role' => 'siswa',
            'username' => 'user-saya',
            'username_changed_at' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('username', 'budi-developer')
            ->call('saveBiodata')
            ->assertHasErrors(['username'])
            ->assertSet('showUsernameConfirmModal', false);

        $user->refresh();
        $this->assertEquals('user-saya', $user->username);
    }

    public function test_mengubah_bio_saja_tidak_memicu_popup_dan_tidak_mereset_cooldown(): void
    {
        $waktuUbah = now()->subDays(3);
        $user = User::factory()->create([
            'role' => 'siswa',
            'username' => 'tetap-sama',
            'username_changed_at' => $waktuUbah,
            'bio' => 'Bio lama',
        ]);

        $this->actingAs($user);

        Livewire::test(ProfilPublikEdit::class)
            ->set('bio', 'Bio baru yang keren')
            ->call('saveBiodata')
            ->assertSet('showUsernameConfirmModal', false)
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals('Bio baru yang keren', $user->bio);
        $this->assertEquals('tetap-sama', $user->username);
        $this->assertEquals($waktuUbah->timestamp, $user->username_changed_at->timestamp);
    }
}
