<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KaryaLog extends Model
{
    protected $fillable = [
        'user_id',
        'karya_siswa_id',
        'admin_id',
        'judul_karya',
        'status',
        'action',
        'catatan',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function karyaSiswa(): BelongsTo
    {
        return $this->belongsTo(KaryaSiswa::class, 'karya_siswa_id');
    }

    /**
     * Catat riwayat status karya baru dan pastikan maksimal 5 log per user (hapus yang paling lama).
     */
    public static function record(
        int $userId,
        string $judulKarya,
        string $status,
        string $action,
        ?string $catatan = null,
        ?int $karyaId = null,
        ?int $adminId = null,
        ?array $metadata = null
    ): self {
        $log = self::create([
            'user_id' => $userId,
            'karya_siswa_id' => $karyaId,
            'admin_id' => $adminId,
            'judul_karya' => $judulKarya,
            'status' => $status,
            'action' => $action,
            'catatan' => $catatan,
            'metadata' => $metadata,
        ]);

        // Pertahankan maksimal 5 log per user. Jika lebih dari 5, hapus dari yang paling lama / paling bawah
        $totalLogs = self::where('user_id', $userId)->count();
        if ($totalLogs > 5) {
            $excessCount = $totalLogs - 5;
            $oldestIds = self::where('user_id', $userId)
                ->oldest('id')
                ->limit($excessCount)
                ->pluck('id');

            self::whereIn('id', $oldestIds)->delete();
        }

        return $log;
    }
}
