<?php

namespace Tests\Feature;

use App\Models\GlobalSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocaleSwitcherTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create default global settings so view composer doesn't fail
        GlobalSetting::create([
            'site_name' => 'Skansaba.Dev',
            'site_tagline' => 'Rekayasa Perangkat Lunak',
            'primary_color' => '#0284c7',
        ]);
    }

    public function test_default_locale_renders_indonesian()
    {
        $response = $this->get('/unit-produksi');

        $response->assertStatus(200);
        $response->assertSee('Beranda');
        $response->assertSee('Unit Produksi');
        $response->assertSee('Harga Jasa Pembuatan Website');
    }

    public function test_switching_to_english_sets_session_and_cookie()
    {
        $response = $this->get('/locale/en');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
        $response->assertCookie('locale', 'en');
        $response->assertCookie('googtrans', '/id/en', false);
    }

    public function test_lang_switch_alias_route_works()
    {
        $response = $this->get('/lang/en');

        $response->assertRedirect('/locale/en');
    }

    public function test_english_locale_renders_english_on_unit_produksi()
    {
        $response = $this->withSession(['locale' => 'en'])->get('/unit-produksi');

        $response->assertStatus(200);
        $response->assertSee('Website Development Pricing');
        $response->assertSee('Turnaround Time:');
        $response->assertSee('Starting From');
        $response->assertSee('Contact Us');
        $response->assertSee('Why Build Your Website with');
        $response->assertSee('Consult Now');
    }

    public function test_switching_back_to_indonesian()
    {
        $response = $this->withSession(['locale' => 'en'])->get('/locale/id');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'id');
        $response->assertCookie('locale', 'id');

        $followResponse = $this->withSession(['locale' => 'id'])->get('/unit-produksi');
        $followResponse->assertStatus(200);
        $followResponse->assertSee('Harga Jasa Pembuatan Website');
        $followResponse->assertSee('Waktu Pengerjaan:');
        $followResponse->assertSee('Hubungi Kami');
    }

    public function test_fresh_visitor_without_session_or_cookie_always_defaults_to_indonesian_id()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertEquals('id', app()->getLocale());
    }

    public function test_invalid_locale_defaults_to_id()
    {
        $response = $this->get('/locale/invalid_lang');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'id');
        $response->assertCookie('locale', 'id');
    }

    public function test_authenticated_sidebar_menu_in_english()
    {
        $siswa = User::create([
            'name' => 'Kalis Lintang',
            'email' => 'kalis@rpl.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'status' => 'active',
        ]);

        $response = $this->actingAs($siswa)
            ->withSession(['locale' => 'en'])
            ->get('/siswa/profil-publik');

        $response->assertStatus(200);
        $response->assertSee('Back to Home');
        $response->assertSee('Public Profile');
        $response->assertSee('My Projects');
        $response->assertSee('Settings');
        $response->assertSee('Customize Public Profile');
    }
}
