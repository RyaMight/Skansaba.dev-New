<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModerationLog extends Model
{
    protected $fillable = [
        'actor_id',
        'target_user_id',
        'action',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /**
     * Catat satu tindakan moderasi/keamanan.
     */
    public static function record(
        ?int $actorId,
        string $action,
        ?string $description = null,
        ?int $targetUserId = null,
        ?array $metadata = null
    ): self {
        return self::create([
            'actor_id' => $actorId,
            'target_user_id' => $targetUserId,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'moderator.granted' => 'Status moderator diberikan',
            'moderator.revoked' => 'Status moderator dicabut',
            'karya.approved' => 'Karya disetujui',
            'karya.rejected' => 'Karya ditolak',
            'account.locked' => 'Akun dinonaktifkan',
            'account.unlocked' => 'Akun diaktifkan',
            'login.unlocked' => 'Blokir login dibuka',
            default => ucfirst(str_replace(['.', '_'], ' ', $this->action)),
        };
    }
}
