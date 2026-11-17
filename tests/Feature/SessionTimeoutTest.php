<?php

use App\Models\User;

test('user otomatis logout setelah 30 menit tanpa aktivitas', function () {
    $user = User::factory()->create(['role' => 'siswa']);

    $this->actingAs($user)
        ->withSession([
            'last_activity_at' => now()->subMinutes(31)->timestamp,
        ])
        ->get('/settings')
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('user tetap login jika aktivitas masih dalam batas 30 menit', function () {
    $user = User::factory()->create(['role' => 'siswa']);

    $this->actingAs($user)
        ->withSession([
            'last_activity_at' => now()->subMinutes(5)->timestamp,
        ])
        ->get('/settings')
        ->assertOk();

    $this->assertAuthenticatedAs($user);
});

test('tamu tidak terpengaruh middleware session timeout', function () {
    $this->get('/login')->assertOk();
});
