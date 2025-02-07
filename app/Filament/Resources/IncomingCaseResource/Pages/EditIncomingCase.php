<?php

namespace App\Filament\Resources\IncomingCaseResource\Pages;

use App\Filament\Resources\IncomingCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIncomingCase extends EditRecord
{
    protected static string $resource = IncomingCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
