<?php

namespace App\Filament\Resources\KematianResource\Pages;

use App\Filament\Resources\KematianResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKematians extends ListRecords
{
    protected static string $resource = KematianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
