<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\QuestionResource;
use App\Models\Question;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Contracts\Support\Htmlable;

class LatestQuestionsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function getTableHeading(): string | Htmlable | null
    {
        return __('Recent Questions from Citizens');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Question::query()->with('topic')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Sender Name'))
                    ->description(fn (Question $record): string => $record->email),

                Tables\Columns\TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->limit(45),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label(__('Topic'))
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'answered' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => __('Pending'),
                        'answered' => __('Answered'),
                        'rejected' => __('Rejected'),
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date Received'))
                    ->since(),
            ])
            ->actions([
                Actions\Action::make('jawab')
                    ->label(__('Open & Answer'))
                    ->icon('heroicon-m-chat-bubble-bottom-center-text')
                    ->url(fn (Question $record): string => QuestionResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
