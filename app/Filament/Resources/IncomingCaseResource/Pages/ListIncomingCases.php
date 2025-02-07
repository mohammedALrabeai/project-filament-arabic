<?php

namespace App\Filament\Resources\IncomingCaseResource\Pages;

use App\Filament\Resources\IncomingCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomingCases extends ListRecords
{
    protected static string $resource = IncomingCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
