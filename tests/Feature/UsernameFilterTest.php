<?php

namespace Tests\Feature;

use App\Livewire\Siswa\ProfilPublikEdit;
use ReflectionMethod;
use Tests\TestCase;

class UsernameFilterTest extends TestCase
{
    private function check(string $username): bool
    {
        $component = new ProfilPublikEdit;
        $method = new ReflectionMethod($component, 'mengandungKataTerlarang');

        return $method->invoke($component, $username);
    }

    public function test_menolak_kata_kasar_indonesia(): void
    {
        $this->assertTrue($this->check('anjing'));
        $this->assertTrue($this->check('babi-gendeng'));
        $this->assertTrue($this->check('tolol123'));
    }

    public function test_menolak_kata_kasar_inggris(): void
    {
        $this->assertTrue($this->check('fuckyou'));
        $this->assertTrue($this->check('shit-happens'));
    }

    public function test_menolak_leetspeak(): void
    {
        $this->assertTrue($this->check('s3x'));
        $this->assertTrue($this->check('b@bi'));
        $this->assertTrue($this->check('f4gg0t'));
    }

    public function test_menerima_username_norml(): void
    {
        $this->assertFalse($this->check('ahmad-syahputra'));
        $this->assertFalse($this->check('budi2026'));
        // Kata yang mengandung substring tak sengaja tetap lolos kecuali cocok blacklist
        $this->assertFalse($this->check('kelas-angkasa'));
    }

    public function test_kata_dalam_kata_lain_tetap_terdeteksi(): void
    {
        $this->assertTrue($this->check('sukababi')); // mengandung "babi"
    }
}
