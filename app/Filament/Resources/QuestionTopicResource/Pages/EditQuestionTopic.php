<?php

namespace App\Filament\Resources\QuestionTopicResource\Pages;

use App\Filament\Resources\QuestionTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;

class EditQuestionTopic extends EditRecord
{
    use Translatable;

    protected static string $resource = QuestionTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
