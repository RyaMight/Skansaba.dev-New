<?php

namespace App\Models;

use App\Traits\HasContentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Services\MarkdownService;

class Berita extends Model
{
    use HasContentStatus, SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'slug',
        'title',
        'excerpt',
        'content_md',
        'thumbnail',
        'author_id',
        'published_at',
        'status',
        'is_highlight',
        'views',
        'meta',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_highlight' => 'boolean',
        'views' => 'integer',
        'meta' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            $source = $berita->slug ?: $berita->title;
            $berita->slug = static::generateUniqueSlug($source);
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('slug') || ($berita->isDirty('title') && empty($berita->slug))) {
                $source = $berita->slug ?: $berita->title;
                $berita->slug = static::generateUniqueSlug($source, $berita->getKey());
            }
        });
    }

    protected static function generateUniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($source);

        if ($baseSlug === '') {
            $baseSlug = 'berita';
        }

        $slug = $baseSlug;
        $suffix = 1;

        while (static::query()
            ->when($ignoreId !== null, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $suffix++;
            $slug = $baseSlug . '-' . $suffix;
        }

        return $slug;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getContentHtmlAttribute(): string
    {
        return $this->content_md
            ? app(MarkdownService::class)->parseDeskripsi($this->content_md)
            : '';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeHighlight($query)
    {
        return $query->where('is_highlight', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
