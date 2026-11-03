<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GaleriImage extends Model
{
    protected $fillable = [
        'galeri_id',
        'file_path',
        'sort_order',
    ];

    public function galeri(): BelongsTo
    {
        return $this->belongsTo(Galeri::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}
