<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\QuestionResource;
use App\Models\Question;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestQuestionsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Pertanyaan Terbaru dari Masyarakat';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Question::query()->with('topic')->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengirim')
                    ->description(fn (Question $record): string => $record->email),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek Pertanyaan')
                    ->limit(45),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topik')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'answered' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'answered' => 'Dijawab',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('jawab')
                    ->label('Buka & Jawab')
                    ->icon('heroicon-m-chat-bubble-bottom-center-text')
                    ->url(fn (Question $record): string => QuestionResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}

