<?php

namespace App\Filament\Resources\PerceraianResource\Pages;

use App\Filament\Resources\PerceraianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerceraian extends EditRecord
{
    protected static string $resource = PerceraianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
