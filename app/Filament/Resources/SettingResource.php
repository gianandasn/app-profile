<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function getModelLabel(): string
    {
        return __('Site Setting');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Site Settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('Site Settings');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->label(__('Group'))
                    ->default('general')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('key')
                    ->label(__('Key'))
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('type')
                    ->label(__('Value Type'))
                    ->options([
                        'text' => __('Short Text'),
                        'textarea' => __('Long Text'),
                        'image' => __('Image / Logo'),
                        'boolean' => __('Boolean (1/0)'),
                        'json' => __('JSON'),
                    ])
                    ->default('text')
                    ->required()
                    ->live(),

                Forms\Components\Textarea::make('value')
                    ->label(__('Value'))
                    ->rows(4)
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('type'), ['text', 'textarea', 'json', 'boolean', null])),

                Forms\Components\FileUpload::make('value')
                    ->label(__('Upload Image'))
                    ->image()
                    ->directory('settings')
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('type') === 'image'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label(__('Group'))
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label(__('Key'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label(__('Value'))
                    ->limit(50),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Value Type'))
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label(__('Group'))
                    ->options([
                        'general' => 'General',
                        'contact' => 'Contact',
                        'social' => 'Social Media',
                        'seo' => 'SEO',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
