<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use Illuminate\Database\Seeder;

class GlobalSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data disinkronkan dari https://skansaba.dev
     */
    public function run(): void
    {
        // Truncate table for idempotency
        GlobalSetting::truncate();

        GlobalSetting::create([
            'site_name' => 'SKANSABA.DEV',
            'site_tagline' => 'Software Engineer SMKN 1 BANTUL',
            'logo_primary' => 'logos/6ade2bd5c84b3_1792945109.webp',
            'logo_secondary' => null,
            'favicon' => 'logos/69e5c1844dd62_1776664964.webp',
            'primary_color' => 'rgb(18,180,224)',
            'secondary_color' => 'rgb(255,255,255)',
            'footer_text' => null,
            'contact_email' => null,
            'contact_phone' => '+62 851-1134-5644',
            'whatsapp' => '6285111345644',
            'address' => 'Jl. Parangtritis No.KM.11, Dukuh, Sabdodadi, Kec. Bantul, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55715',
            'facebook' => null,
            'instagram' => 'https://www.instagram.com/skansaba.dev',
            'twitter' => null,
            'youtube' => null,
            'linkedin' => null,
            'tiktok' => null,
            'maps_url' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.049281693703!2d110.35305437500601!3d-7.889913292132739!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7b00889ad8f84d%3A0x2e0009ca7815eaf0!2sSMK%20Negeri%201%20Bantul!5e0!3m2!1sid!2sid!4v1776395696979!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
        ]);

        $this->command->info('✓ Global settings seeded successfully (1 record) - data skansaba.dev');
    }
}
