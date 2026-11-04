<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Notifications\QuestionAnsweredNotification;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;

class QuestionAnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $title = 'Tanggapan / Jawaban';

    protected static ?string $recordTitleAttribute = 'body';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\RichEditor::make('body')
                    ->label('Isi Jawaban / Penjelasan')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->label('Status Jawaban')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Dipublikasikan (Dapat dilihat publik)',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('published')
                    ->required(),

                Forms\Components\Toggle::make('is_published')
                    ->label('Tampilkan ke Publik')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Dijawab Oleh')
                    ->placeholder('Petugas'),

                Tables\Columns\TextColumn::make('body')
                    ->label('Jawaban')
                    ->html()
                    ->limit(80),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),

                Tables\Columns\TextColumn::make('answered_at')
                    ->label('Waktu Menjawab')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['answered_at'] = now();
                        return $data;
                    })
                    ->after(function (QuestionAnswer $record) {
                        /** @var Question $question */
                        $question = $this->getOwnerRecord();

                        if ($record->status === 'published' || $record->is_published) {
                            $question->update(['status' => 'answered']);

                            if (! empty($question->email)) {
                                try {
                                    Notification::route('mail', $question->email)
                                        ->notify(new QuestionAnsweredNotification($question, $record));
                                } catch (\Throwable $e) {
                                    // Ignore mail failures in dev environments without mailer configured
                                }
                            }
                        }

                        FilamentNotification::make()
                            ->title('Jawaban berhasil disimpan')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function (QuestionAnswer $record) {
                        /** @var Question $question */
                        $question = $this->getOwnerRecord();

                        if ($record->status === 'published' || $record->is_published) {
                            $question->update(['status' => 'answered']);
                        }
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
