<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'slug', 'description', 'icon', 'sort_order', 'is_active'])]
class Category extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = [
        'name',
        'slug',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $name = $category->getTranslation('name', app()->getLocale(), false) ?: (is_string($category->name) ? $category->name : '');
                $category->setTranslation('slug', app()->getLocale(), Str::slug($name));
            }
        });
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
