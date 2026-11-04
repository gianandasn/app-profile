<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string | \UnitEnum | null $navigationGroup = 'Pengaturan';

    protected static ?string $modelLabel = 'Pengaturan Situs';

    protected static ?string $pluralModelLabel = 'Pengaturan Situs';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->label('Grup')
                    ->default('general')
                    ->required()
                    ->maxLength(50),

                Forms\Components\TextInput::make('key')
                    ->label('Kunci (Key)')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('type')
                    ->label('Tipe Nilai')
                    ->options([
                        'text' => 'Teks Singkat',
                        'textarea' => 'Teks Panjang',
                        'image' => 'Gambar / Logo',
                        'boolean' => 'Boolean (1/0)',
                        'json' => 'JSON',
                    ])
                    ->default('text')
                    ->required()
                    ->live(),

                Forms\Components\Textarea::make('value')
                    ->label('Nilai (Value)')
                    ->rows(4)
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('type'), ['text', 'textarea', 'json', 'boolean', null])),

                Forms\Components\FileUpload::make('value')
                    ->label('Upload Gambar')
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
                    ->label('Grup')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('key')
                    ->label('Kunci')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->limit(50),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'contact' => 'Contact',
                        'social' => 'Social Media',
                        'seo' => 'SEO',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
