<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GlobalSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_tagline',
        'logo_primary',
        'logo_secondary',
        'favicon',
        'primary_color',
        'secondary_color',
        'footer_text',
        'contact_email',
        'contact_phone',
        'whatsapp',
        'address',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'tiktok',
        'maps_url',
    ];

    /**
     * Get the singleton settings record.
     * Creates one if it doesn't exist.
     */
    public static function instance()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'site_name' => 'SMK RPL',
                'primary_color' => 'rgb(18,180,224)',
                'secondary_color' => 'rgb(255,255,255)',
            ]);
        }
        
        return $settings;
    }

    /**
     * Get URL for logo_primary (falls back to the current favicon when the
     * stored logo file is missing, e.g. legacy/broken upload paths).
     */
    public function getLogoPrimaryUrlAttribute()
    {
        $fallback = 'logos/favicon_skansaba.webp';

        if ($this->logo_primary && Storage::disk('public')->exists($this->logo_primary)) {
            return Storage::url($this->logo_primary);
        }

        return Storage::disk('public')->exists($fallback) ? Storage::url($fallback) : null;
    }

    /**
     * Get URL for logo_secondary
     */
    public function getLogoSecondaryUrlAttribute()
    {
        return $this->logo_secondary ? Storage::url($this->logo_secondary) : null;
    }

    /**
     * Get URL for favicon (falls back to the current bundled favicon when the
     * stored file is missing, e.g. legacy/broken upload paths).
     */
    public function getFaviconUrlAttribute()
    {
        $fallback = 'logos/favicon_skansaba.webp';

        if ($this->favicon && Storage::disk('public')->exists($this->favicon)) {
            return Storage::url($this->favicon);
        }

        return Storage::disk('public')->exists($fallback) ? Storage::url($fallback) : null;
    }

    /**
     * Get primary color as CSS style
     */
    public function getPrimaryColorStyleAttribute()
    {
        return $this->primary_color ?: 'rgb(18,180,224)';
    }

    /**
     * Get secondary color as CSS style
     */
    public function getSecondaryColorStyleAttribute()
    {
        return $this->secondary_color ?: 'rgb(255,255,255)';
    }

    /**
     * Get sanitized digits for WhatsApp (default: 6285111345644)
     */
    public function getWhatsappDigitsAttribute()
    {
        $raw = $this->whatsapp ?: $this->contact_phone ?: '6285111345644';
        $digits = preg_replace('/[^0-9]/', '', $raw);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }
        return $digits ?: '6285111345644';
    }

    /**
     * Get direct wa.me chat URL
     */
    public function getWhatsappUrlAttribute()
    {
        return 'https://wa.me/' . $this->whatsapp_digits;
    }

    /**
     * Clear cache when settings are updated
     */
    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('global_settings');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('global_settings');
        });
    }
}
