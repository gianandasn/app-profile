<?php

namespace App\Filament\Widgets;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Post;
use App\Models\Question;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Artikel Terbit', Post::query()->published()->count())
                ->description('Artikel publik yang sudah tayang')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Halaman', Page::query()->published()->count())
                ->description('Halaman profil instansi aktif')
                ->color('info'),

            Stat::make('Pertanyaan Menunggu', Question::query()->pending()->count())
                ->description('Perlu respon dari petugas')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total FAQ Aktif', Faq::query()->active()->count())
                ->description('Informasi tanya jawab umum')
                ->color('primary'),
        ];
    }
}

