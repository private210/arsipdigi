<?php

namespace App\Filament\Resources\PernikahanResource\Pages;

use App\Filament\Resources\PernikahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPernikahans extends ListRecords
{
    protected static string $resource = PernikahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
