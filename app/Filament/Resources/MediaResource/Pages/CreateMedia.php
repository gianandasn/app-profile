<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['disk'] = 'public';

        if (! empty($data['file_path'])) {
            $path = $data['file_path'];
            if (empty($data['file_name'])) {
                $data['file_name'] = basename($path);
            }

            if (Storage::disk('public')->exists($path)) {
                $data['mime_type'] = Storage::disk('public')->mimeType($path) ?? 'application/octet-stream';
                $data['file_size'] = Storage::disk('public')->size($path) ?? 0;
            } else {
                $data['mime_type'] = 'application/octet-stream';
                $data['file_size'] = 0;
            }
        }

        return $data;
    }
}

