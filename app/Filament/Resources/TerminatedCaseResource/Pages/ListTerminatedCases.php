<?php

namespace App\Filament\Resources\TerminatedCaseResource\Pages;

use App\Filament\Resources\TerminatedCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTerminatedCases extends ListRecords
{
    protected static string $resource = TerminatedCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
