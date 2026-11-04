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
            Stat::make(__('Total Published Posts'), Post::query()->published()->count())
                ->description(__('Live public articles'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make(__('Total Pages'), Page::query()->published()->count())
                ->description(__('Active institutional profile pages'))
                ->color('info'),

            Stat::make(__('Pending Questions'), Question::query()->pending()->count())
                ->description(__('Needs response from staff'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make(__('Total Active FAQs'), Faq::query()->active()->count())
                ->description(__('General Q&A information'))
                ->color('primary'),
        ];
    }
}
