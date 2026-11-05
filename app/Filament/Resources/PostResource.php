<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;

class PostResource extends Resource
{
    use Translatable;

    protected static ?string $model = Post::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function getModelLabel(): string
    {
        return __('Post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Posts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('CMS');
    }

    public static function getNavigationLabel(): string
    {
        return __('Posts');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                modifyRuleUsing: fn ($rule, $livewire) => $rule->where('slug->' . ($livewire->activeLocale ?? 'id')),
                                ignoreRecord: true
                            ),

                        Forms\Components\Textarea::make('summary')
                            ->label(__('Summary'))
                            ->rows(3),

                        Forms\Components\RichEditor::make('content')
                            ->label(__('Content'))
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                \Filament\Schemas\Components\Group::make()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'draft' => __('Draft'),
                                'published' => __('Published'),
                                'archived' => __('Archived'),
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Select::make('category_id')
                            ->label(__('Category'))
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label(__('Published At')),

                        Forms\Components\Toggle::make('is_featured')
                            ->label(__('Featured')),

                        Forms\Components\FileUpload::make('image')
                            ->label(__('Image'))
                            ->image()
                            ->directory('posts')
                            ->imageEditor(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(__('Image'))
                    ->circular(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (Post $record): ?string => $record->summary ? Str::limit($record->summary, 50) : null),

                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => __('Draft'),
                        'published' => __('Published'),
                        'archived' => __('Archived'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label(__('Featured'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('view_count')
                    ->label(__('Views'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('Published At'))
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('Author'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'draft' => __('Draft'),
                        'published' => __('Published'),
                        'archived' => __('Archived'),
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->label(__('Category'))
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label(__('Featured')),
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
