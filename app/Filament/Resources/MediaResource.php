<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class MediaResource extends Resource
{
    use Translatable;

    protected static ?string $model = Media::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-photo';

    public static function getModelLabel(): string
    {
        return __('Media');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Media Library');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('CMS');
    }

    public static function getNavigationLabel(): string
    {
        return __('Media Library');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\FileUpload::make('file_path')
                    ->label(__('File'))
                    ->directory('media')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('file_name')
                    ->label(__('File Name'))
                    ->maxLength(255),

                Forms\Components\TextInput::make('alt_text')
                    ->label(__('Alt Text'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('file_path')
                    ->label(__('Preview'))
                    ->square(),

                Tables\Columns\TextColumn::make('file_name')
                    ->label(__('File Name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mime_type')
                    ->label(__('Mime Type'))
                    ->badge(),

                Tables\Columns\TextColumn::make('file_size')
                    ->label(__('File Size'))
                    ->formatStateUsing(function ($state) {
                        if (! $state) return '-';
                        if ($state >= 1048576) {
                            return round($state / 1048576, 2) . ' MB';
                        }
                        return round($state / 1024, 2) . ' KB';
                    }),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Uploaded By'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Date'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
