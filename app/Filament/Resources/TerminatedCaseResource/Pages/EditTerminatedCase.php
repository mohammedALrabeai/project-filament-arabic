<?php

namespace App\Filament\Resources\TerminatedCaseResource\Pages;

use App\Filament\Resources\TerminatedCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTerminatedCase extends EditRecord
{
    protected static string $resource = TerminatedCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
