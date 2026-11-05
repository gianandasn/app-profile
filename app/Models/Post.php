<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

#[Fillable(['user_id', 'category_id', 'title', 'slug', 'summary', 'content', 'image', 'status', 'published_at', 'view_count', 'is_featured'])]
class Post extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = [
        'title',
        'slug',
        'summary',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'view_count' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $title = $post->getTranslation('title', app()->getLocale(), false) ?: (is_string($post->title) ? $post->title : '');
                $post->setTranslation('slug', app()->getLocale(), Str::slug($title));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
