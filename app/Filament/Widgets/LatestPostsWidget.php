<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class LatestPostsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getTableHeading(): string | Htmlable | null
    {
        return __('Recent Posts & News');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::query()->with('category')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('Thumbnail'))
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(45),

                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => __('Draft'),
                        'published' => __('Published'),
                        'archived' => __('Archived'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'gray',
                        'archived' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('view_count')
                    ->label(__('Views'))
                    ->numeric(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->since(),
            ])
            ->actions([
                Actions\Action::make('edit')
                    ->label(__('Edit'))
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
