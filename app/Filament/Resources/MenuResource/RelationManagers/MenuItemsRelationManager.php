<?php

namespace App\Filament\Resources\MenuResource\RelationManagers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Item Menu';

    protected static ?string $recordTitleAttribute = 'label';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Label')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->label('Tipe Menu')
                    ->options([
                        'custom' => 'Tautan Kustom (URL)',
                        'page' => 'Halaman Statis',
                        'post' => 'Artikel / Berita',
                        'category' => 'Kategori Artikel',
                    ])
                    ->default('custom')
                    ->required()
                    ->live(),

                Forms\Components\TextInput::make('url')
                    ->label('URL')
                    ->placeholder('https://contoh.com atau /kontak')
                    ->visible(fn ($get) => $get('type') === 'custom')
                    ->required(fn ($get) => $get('type') === 'custom')
                    ->maxLength(255),

                Forms\Components\Select::make('linkable_id')
                    ->label('Pilih Konten')
                    ->options(function (Get $get) {
                        return match ($get('type')) {
                            'page' => Page::query()->orderBy('title')->pluck('title', 'id'),
                            'post' => Post::query()->published()->orderBy('title')->pluck('title', 'id'),
                            'category' => Category::query()->active()->orderBy('name')->pluck('name', 'id'),
                            default => [],
                        };
                    })
                    ->visible(fn ($get) => in_array($get('type'), ['page', 'post', 'category']))
                    ->required(fn ($get) => in_array($get('type'), ['page', 'post', 'category']))
                    ->searchable()
                    ->live(),

                Forms\Components\Select::make('parent_id')
                    ->label('Menu Induk (Sub-menu dari)')
                    ->relationship('parent', 'label', fn ($query) => $query->where('menu_id', $this->getOwnerRecord()->id))
                    ->searchable()
                    ->nullable(),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'page' => 'info',
                        'post' => 'success',
                        'category' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL / Target')
                    ->limit(30),

                Tables\Columns\TextColumn::make('parent.label')
                    ->label('Induk')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->headerActions([
                Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if ($data['type'] === 'page') {
                            $data['linkable_type'] = Page::class;
                        } elseif ($data['type'] === 'post') {
                            $data['linkable_type'] = Post::class;
                        } elseif ($data['type'] === 'category') {
                            $data['linkable_type'] = Category::class;
                        } else {
                            $data['linkable_type'] = null;
                        }
                        return $data;
                    }),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        if ($data['type'] === 'page') {
                            $data['linkable_type'] = Page::class;
                        } elseif ($data['type'] === 'post') {
                            $data['linkable_type'] = Post::class;
                        } elseif ($data['type'] === 'category') {
                            $data['linkable_type'] = Category::class;
                        } else {
                            $data['linkable_type'] = null;
                        }
                        return $data;
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
