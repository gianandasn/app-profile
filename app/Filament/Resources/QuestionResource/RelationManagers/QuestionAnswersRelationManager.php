<?php

namespace App\Filament\Resources\QuestionResource\RelationManagers;

use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Notifications\QuestionAnsweredNotification;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class QuestionAnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $recordTitleAttribute = 'body';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Responses / Answers');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\RichEditor::make('body')
                    ->label(__('Answer / Explanation'))
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Select::make('status')
                    ->label(__('Answer Status'))
                    ->options([
                        'draft' => __('Draft'),
                        'published' => __('Published (Publicly Visible)'),
                        'rejected' => __('Rejected'),
                    ])
                    ->default('published')
                    ->required(),

                Forms\Components\Toggle::make('is_published')
                    ->label(__('Publicly Visible'))
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Answered By'))
                    ->placeholder(__('Author')),

                Tables\Columns\TextColumn::make('body')
                    ->label(__('Answer'))
                    ->html()
                    ->limit(80),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => __('Draft'),
                        'published' => __('Published'),
                        'rejected' => __('Rejected'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_published')
                    ->label(__('Public'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('answered_at')
                    ->label(__('Answered At'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Actions\CreateAction::make()
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
                            ->title(__('Answer saved successfully'))
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->after(function (QuestionAnswer $record) {
                        /** @var Question $question */
                        $question = $this->getOwnerRecord();

                        if ($record->status === 'published' || $record->is_published) {
                            $question->update(['status' => 'answered']);
                        }
                    }),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
