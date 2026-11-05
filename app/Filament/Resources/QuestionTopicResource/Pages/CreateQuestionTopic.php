<?php

namespace App\Filament\Resources\QuestionTopicResource\Pages;

use App\Filament\Resources\QuestionTopicResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateQuestionTopic extends CreateRecord
{
    use Translatable;

    protected static string $resource = QuestionTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
