<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers\QuestionAnswersRelationManager;
use App\Models\Question;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-question-mark-circle';

    public static function getModelLabel(): string
    {
        return __('Citizen Question');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Citizen Questions');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Citizen Questions');
    }

    public static function getNavigationLabel(): string
    {
        return __('Citizen Questions');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Select::make('topic_id')
                    ->label(__('Topic'))
                    ->relationship('topic', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('Pending (Awaiting Response)'),
                        'answered' => __('Answered'),
                        'rejected' => __('Rejected'),
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->label(__('Sender Name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label(__('Sender Email'))
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label(__('Phone / WhatsApp Number'))
                    ->tel()
                    ->maxLength(50),

                Forms\Components\TextInput::make('ip_address')
                    ->label(__('IP Address'))
                    ->disabled(),

                Forms\Components\TextInput::make('locale')
                    ->label(__('Language'))
                    ->disabled()
                    ->default('id'),

                Forms\Components\TextInput::make('subject')
                    ->label(__('Subject / Question Title'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('body')
                    ->label(__('Question Body'))
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Sender'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (Question $record): string => $record->email),

                Tables\Columns\TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label(__('Topic'))
                    ->badge(),

                Tables\Columns\TextColumn::make('locale')
                    ->label(__('Language'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => strtoupper($state ?? 'ID')),

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
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('Pending (Awaiting Response)'),
                        'answered' => __('Answered'),
                        'rejected' => __('Rejected'),
                    ]),
                Tables\Filters\SelectFilter::make('topic')
                    ->relationship('topic', 'name')
                    ->label(__('Topic')),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            QuestionAnswersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
