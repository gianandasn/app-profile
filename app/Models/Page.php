<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

#[Fillable(['user_id', 'title', 'slug', 'content', 'image', 'status', 'sort_order', 'show_in_menu'])]
class Page extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = [
        'title',
        'slug',
        'content',
    ];

    protected function casts(): array
    {
        return [
            'show_in_menu' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Page $page) {
            if (empty($page->slug)) {
                $title = $page->getTranslation('title', app()->getLocale(), false) ?: (is_string($page->title) ? $page->title : '');
                $page->setTranslation('slug', app()->getLocale(), Str::slug($title));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeInMenu(Builder $query): Builder
    {
        return $query->where('show_in_menu', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
