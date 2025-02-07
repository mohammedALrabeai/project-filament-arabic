<?php

namespace App\Filament\Resources\TerminatedCaseResource\Pages;

use App\Filament\Resources\TerminatedCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTerminatedCase extends CreateRecord
{
    protected static string $resource = TerminatedCaseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
