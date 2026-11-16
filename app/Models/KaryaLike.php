<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KaryaLike extends Model
{
    protected $fillable = [
        'karya_siswa_id',
        'user_id',
        'guest_token',
    ];

    public function karya(): BelongsTo
    {
        return $this->belongsTo(KaryaSiswa::class, 'karya_siswa_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}