<?php

namespace App\Filament\Resources\SyncEventResource\Pages;

use App\Filament\Resources\SyncEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSyncEvent extends EditRecord
{
    protected static string $resource = SyncEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
