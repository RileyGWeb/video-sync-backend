<?php

namespace App\Filament\Resources\BitsTransactionResource\Pages;

use App\Filament\Resources\BitsTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBitsTransactions extends ListRecords
{
    protected static string $resource = BitsTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
