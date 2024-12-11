<?php

namespace App\Filament\Resources\KematianResource\Pages;

use App\Filament\Resources\KematianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKematian extends EditRecord
{
    protected static string $resource = KematianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
