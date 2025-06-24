<?php

namespace App\Filament\Resources\VideoSessionResource\Pages;

use App\Filament\Resources\VideoSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVideoSession extends EditRecord
{
    protected static string $resource = VideoSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
