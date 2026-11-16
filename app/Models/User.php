<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Livewire\KaryaSiswaVerifikasi;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const DEFAULT_ADMIN_PASSWORD = 'RPLskansabaselalujaya';

    public const DEFAULT_GURU_PASSWORD = 'GURURPLJAYA';

    public const DEFAULT_SISWA_PASSWORD = 'SKANSABAJAYA321';

    public const MAX_TECH_STACK = 5;

    /**
     * @var list<string>
     */
    public const PHOTO_FRAMES = ['circle', 'squircle'];

    /**
     * Daftar nama role / prospek karir yang tidak boleh diisi sendiri oleh siswa
     * di kolom Tech Stack karena hanya boleh diberikan oleh Admin/Guru.
     */
    public const FORBIDDEN_ROLE_TAGS = [
        'web development',
        'web developer',
        'webdev',
        'web dev',
        'mobile development',
        'mobile developer',
        'mobile dev',
        'cyber security',
        'game development',
        'game developer',
        'ui/ux design',
        'ui/ux designer',
        'ui ux',
        'ui/ux',
        'iot & embedded',
        'ai & machine learning',
        'data science',
        'data scientist',
        'desain grafis',
        'graphic design',
        'graphic designer',
        'animasi & 3d',
        'robotika',
        'jaringan komputer',
        'devops & cloud',
        'devops',
        'open source',
        'fullstack developer',
        'full-stack developer',
        'full stack developer',
        'frontend developer',
        'front-end developer',
        'backend developer',
        'back-end developer',
        'software engineer',
        'qa engineer',
        'system analyst',
        'it support',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nisn',
        'kelas',
        'bio',
        'profil_readme',
        'username',
        'username_changed_at',
        'phone',
        'keahlian',
        'verified_tags',
        'saluran_kontak',
        'email',
        'password',
        'role',
        'is_moderator',
        'status',
        'photo',
        'photo_frame',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'username_changed_at' => 'datetime',
            'saluran_kontak' => 'array',
            'is_moderator' => 'boolean',
        ];
    }

    /**
     * Get the user's photo URL
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        if (Storage::disk('public')->exists($this->photo)) {
            return Storage::url($this->photo);
        }

        return null;
    }

    public static function normalizePhotoFrame(?string $frame): string
    {
        return in_array($frame, self::PHOTO_FRAMES, true) ? $frame : 'circle';
    }

    public function photoFrameClass(string $squircleClass = 'rounded-2xl', string $circleClass = 'rounded-full'): string
    {
        return $this->photo_frame === 'squircle' ? $squircleClass : $circleClass;
    }

    /**
     * Parse comma-separated tag list into a clean, deduplicated array.
     */
    public static function parseCommaList(?string $value): array
    {
        if (! $value) {
            return [];
        }

        $items = [];
        $seen = [];

        foreach (explode(',', $value) as $raw) {
            $trimmed = trim($raw);
            if ($trimmed === '') {
                continue;
            }
            $key = mb_strtolower($trimmed);
            if (! isset($seen[$key])) {
                $seen[$key] = true;
                $items[] = $trimmed;
            }
        }

        return $items;
    }

    /**
     * Normalize a comma-separated tech stack string.
     */
    public static function normalizeTechStack(?string $value): ?string
    {
        $items = static::parseCommaList($value);

        return count($items) > 0 ? implode(', ', $items) : null;
    }

    /**
     * Cek apakah tag termasuk role/prospek karir yang hanya boleh diberikan Admin.
     */
    public static function isForbiddenRoleTag(string $tag): bool
    {
        $normalized = mb_strtolower(preg_replace('/\s+/', ' ', trim($tag)));
        if ($normalized === '') {
            return false;
        }

        $blocked = array_map('mb_strtolower', array_merge(
            static::FORBIDDEN_ROLE_TAGS,
            KaryaSiswaVerifikasi::TAG_PRESETS
        ));

        try {
            if (Schema::hasTable('prospek_karirs')) {
                $prospekTitles = ProspekKarir::query()->pluck('title')->all();
                foreach ($prospekTitles as $title) {
                    $blocked[] = mb_strtolower(preg_replace('/\s+/', ' ', trim((string) $title)));
                }
            }
        } catch (\Throwable) {
            // Abaikan jika tabel belum dimigrasi saat unit test terisolasi
        }

        return in_array($normalized, array_unique($blocked), true);
    }

    /**
     * Rule validasi Tech Stack siswa: maks 5 item & bebas dari nama role/prospek karir admin.
     */
    public static function techStackValidationRule(): \Closure
    {
        return function (string $attribute, mixed $value, \Closure $fail): void {
            $items = static::parseCommaList((string) $value);

            if (count($items) > static::MAX_TECH_STACK) {
                $fail('Maksimal '.static::MAX_TECH_STACK.' keahlian / tech stack yang dapat ditambahkan agar profil tetap ringkas.');

                return;
            }

            foreach ($items as $item) {
                if (mb_strlen($item) > 40) {
                    $fail("Nama keahlian \"{$item}\" terlalu panjang (maksimal 40 karakter per item).");

                    return;
                }

                if (static::isForbiddenRoleTag($item)) {
                    $fail("Tag \"{$item}\" adalah role/prospek karir terverifikasi yang hanya dapat diberikan oleh Admin melalui verifikasi karya.");

                    return;
                }
            }
        };
    }

    /**
     * Tech Stack siswa sebagai array (diisi siswa, maks 5).
     */
    public function getKeahlianListAttribute(): array
    {
        return static::parseCommaList($this->keahlian);
    }

    /**
     * Tag Role / Prospek Karir terverifikasi sebagai array (khusus diberikan Admin/Guru).
     */
    public function getVerifiedTagsListAttribute(): array
    {
        return static::parseCommaList($this->verified_tags);
    }

    /**
     * Berikan tag role terverifikasi tambahan dari Admin (tidak menimpa tag yang sudah ada)
     */
    public function giveTag(string $tag): bool
    {
        $tag = trim($tag);
        if (! $tag) {
            return false;
        }

        $tags = $this->verified_tags_list;
        if (! in_array($tag, $tags, true)) {
            $tags[] = $tag;

            return $this->update(['verified_tags' => implode(', ', $tags)]);
        }

        return false;
    }

    /**
     * Cabut tag role terverifikasi dari akun siswa
     */
    public function revokeTag(string $tag): bool
    {
        $tag = trim($tag);
        $tags = $this->verified_tags_list;
        if (in_array($tag, $tags, true)) {
            $tags = array_values(array_diff($tags, [$tag]));

            return $this->update(['verified_tags' => count($tags) > 0 ? implode(', ', $tags) : null]);
        }

        return false;
    }

    /**
     * Periksa apakah akun siswa sudah memiliki tag role terverifikasi ini
     */
    public function hasTag(string $tag): bool
    {
        return in_array(trim($tag), $this->verified_tags_list, true);
    }

    /**
     * Resolve a user by profile slug: numeric ID or custom username.
     */
    public static function resolveProfile(string $slug): ?self
    {
        $query = static::where('status', 'active');

        if (ctype_digit($slug)) {
            return $query->whereKey((int) $slug)->first();
        }

        return $query->where('username', $slug)->first();
    }

    /**
     * Public profile URL (uses custom username when available)
     */
    public function getProfilUrlAttribute(): string
    {
        return route('profil.public', $this->username ?: $this->id);
    }

    /**
     * Cek apakah user boleh mengubah username custom (cooldown 7 hari).
     */
    public function canChangeUsername(): bool
    {
        if (is_null($this->username_changed_at)) {
            return true;
        }

        return $this->username_changed_at->copy()->addDays(7)->isPast();
    }

    /**
     * Waktu kapan user boleh mengubah username lagi.
     */
    public function usernameAvailableAgainAt(): ?CarbonInterface
    {
        if (is_null($this->username_changed_at)) {
            return null;
        }

        return $this->username_changed_at->copy()->addDays(7);
    }

    /**
     * Sisa hari (ceil) cooldown ganti username.
     */
    public function daysUntilNextUsernameChange(): int
    {
        $next = $this->usernameAvailableAgainAt();
        if (! $next || $next->isPast()) {
            return 0;
        }

        return (int) ceil(now()->floatDiffInDays($next));
    }

    /**
     * Get role badge color
     */
    public function getRoleBadgeColorAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'bg-purple-500/10 text-purple-700',
            'guru' => 'bg-blue-500/10 text-blue-700',
            'siswa' => 'bg-green-500/10 text-green-700',
            default => 'bg-gray-500/10 text-gray-700',
        };
    }

    /**
     * Get role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Administrator',
            'guru' => 'Guru',
            'siswa' => 'Siswa',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-green-500/10 text-green-700',
            'inactive' => 'bg-red-500/10 text-red-700',
            default => 'bg-gray-500/10 text-gray-700',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Nonaktif',
            default => 'Unknown',
        };
    }

    /**
     * Scope to filter by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is guru
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Check if user is siswa
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Siswa yang diberi mandat admin untuk membantu pengawasan karya & keamanan.
     */
    public function isModerator(): bool
    {
        return $this->isSiswa() && (bool) $this->is_moderator;
    }

    /**
     * Boleh melakukan verifikasi karya (admin, guru, atau moderator).
     */
    public function canVerifyKarya(): bool
    {
        return $this->isAdmin() || $this->isGuru() || $this->isModerator();
    }

    /**
     * Label role untuk tampilan internal (mis. sidebar & tabel pengguna).
     */
    public function getDisplayRoleLabelAttribute(): string
    {
        return $this->isModerator() ? 'Siswa · Moderator' : $this->role_label;
    }

    /**
     * Warna badge untuk label role yang sudah mempertimbangkan status moderator.
     */
    public function getDisplayRoleBadgeColorAttribute(): string
    {
        return $this->isModerator()
            ? 'bg-amber-500/10 text-amber-700 dark:text-amber-300'
            : $this->role_badge_color;
    }

    /**
     * Periksa apakah akun siswa masih menggunakan password default bawaan.
     * Hasil disimpan di session per user_id agar tidak membebani bcrypt di tiap request.
     */
    public function isUsingDefaultPassword(): bool
    {
        if (! $this->isSiswa()) {
            return false;
        }

        $sessionKey = 'using_default_password_'.$this->id;

        if (session()->has($sessionKey)) {
            return (bool) session($sessionKey);
        }

        $isDefault = Hash::check(self::DEFAULT_SISWA_PASSWORD, $this->password);

        session([$sessionKey => $isDefault]);

        return $isDefault;
    }

    /**
     * Tandai di session bahwa password user sudah bukan default lagi.
     */
    public function clearDefaultPasswordFlag(): void
    {
        session([
            'using_default_password_'.$this->id => false,
            'default_password_modal_dismissed_'.$this->id => true,
        ]);
    }

    /**
     * Check if user has permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $guruPermissions = ['view_dashboard', 'create_content', 'edit_own_content'];

        return in_array($permission, $guruPermissions);
    }

    public function karyaSiswas(): HasMany
    {
        return $this->hasMany(KaryaSiswa::class);
    }
}
