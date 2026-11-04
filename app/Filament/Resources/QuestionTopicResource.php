<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionTopicResource\Pages;
use App\Models\QuestionTopic;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class QuestionTopicResource extends Resource
{
    protected static ?string $model = QuestionTopic::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getModelLabel(): string
    {
        return __('Question Topic');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Question Topics');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Citizen Questions');
    }

    public static function getNavigationLabel(): string
    {
        return __('Question Topics');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->label(__('Slug'))
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\TextInput::make('icon')
                    ->label(__('Icon'))
                    ->placeholder('heroicon-o-information-circle')
                    ->maxLength(255),

                Forms\Components\TextInput::make('sort_order')
                    ->label(__('Sort Order'))
                    ->numeric()
                    ->default(0),

                Forms\Components\Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(3)
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('is_active')
                    ->label(__('Active'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('Slug')),

                Tables\Columns\TextColumn::make('questions_count')
                    ->label(__('Questions Count'))
                    ->counts('questions')
                    ->sortable(),

                Tables\Columns\TextColumn::make('faqs_count')
                    ->label(__('FAQs Count'))
                    ->counts('faqs')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('Sort Order'))
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label(__('Active')),
            ])
            ->defaultSort('sort_order', 'asc')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestionTopics::route('/'),
            'create' => Pages\CreateQuestionTopic::route('/create'),
            'edit' => Pages\EditQuestionTopic::route('/{record}/edit'),
        ];
    }
}
