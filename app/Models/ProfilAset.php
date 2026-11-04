<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProfilAset extends Model
{
    protected $fillable = [
        'user_id',
        'nama_file',
        'path',
        'size',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Public URL of the stored SVG (disk: public).
     */
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Markdown snippet the student can paste into their README.
     */
    public function getSnippetAttribute(): string
    {
        $alt = pathinfo($this->nama_file, PATHINFO_FILENAME) ?: 'aset-svg';

        return '!['.$alt.']('.$this->url.')';
    }

    /**
     * Human-readable file size.
     */
    public function getSizeHumanAttribute(): string
    {
        $bytes = $this->size;

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1).' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 1).' KB';
        }

        return $bytes.' B';
    }
}
