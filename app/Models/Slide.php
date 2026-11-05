<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

#[Fillable(['title', 'description', 'image', 'link_url', 'sort_order', 'is_active', 'start_date', 'end_date'])]
class Slide extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = [
        'title',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function scopeCurrent(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $today);
            });
    }
}
