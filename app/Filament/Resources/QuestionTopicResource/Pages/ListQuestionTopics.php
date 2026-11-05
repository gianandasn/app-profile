<?php

namespace App\Filament\Resources\QuestionTopicResource\Pages;

use App\Filament\Resources\QuestionTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListQuestionTopics extends ListRecords
{
    use Translatable;

    protected static string $resource = QuestionTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            Actions\CreateAction::make(),
        ];
    }
}
