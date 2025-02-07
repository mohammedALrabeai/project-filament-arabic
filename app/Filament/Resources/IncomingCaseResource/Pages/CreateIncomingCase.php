<?php

namespace App\Filament\Resources\IncomingCaseResource\Pages;

use App\Filament\Resources\IncomingCaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateIncomingCase extends CreateRecord
{
    protected static string $resource = IncomingCaseResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
