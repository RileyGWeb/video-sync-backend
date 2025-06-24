<?php

namespace App\Filament\Resources\SyncEventResource\Pages;

use App\Filament\Resources\SyncEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSyncEvents extends ListRecords
{
    protected static string $resource = SyncEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
