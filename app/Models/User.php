<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

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
        'saluran_kontak',
        'email',
        'password',
        'role',
        'status',
        'photo',
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

    /**
     * Keahlian as array (comma-separated string in DB)
     */
    public function getKeahlianListAttribute(): array
    {
        if (!$this->keahlian) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $this->keahlian))));
    }

    /**
     * Berikan tag keahlian terverifikasi tambahan (tidak menimpa tag yang sudah ada)
     */
    public function giveTag(string $tag): bool
    {
        $tag = trim($tag);
        if (!$tag) {
            return false;
        }

        $tags = $this->keahlian_list;
        if (!in_array($tag, $tags, true)) {
            $tags[] = $tag;
            return $this->update(['keahlian' => implode(', ', $tags)]);
        }
        return false;
    }

    /**
     * Cabut tag keahlian terverifikasi dari akun siswa
     */
    public function revokeTag(string $tag): bool
    {
        $tag = trim($tag);
        $tags = $this->keahlian_list;
        if (in_array($tag, $tags, true)) {
            $tags = array_values(array_diff($tags, [$tag]));
            return $this->update(['keahlian' => count($tags) > 0 ? implode(', ', $tags) : null]);
        }
        return false;
    }

    /**
     * Periksa apakah akun siswa sudah memiliki tag keahlian ini
     */
    public function hasTag(string $tag): bool
    {
        return in_array(trim($tag), $this->keahlian_list, true);
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
    public function usernameAvailableAgainAt(): ?\Carbon\CarbonInterface
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
        return match($this->role) {
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
        return match($this->role) {
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
        return match($this->status) {
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
        return match($this->status) {
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

    public function karyaSiswas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KaryaSiswa::class);
    }
}
