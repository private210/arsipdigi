<?php

namespace App\Filament\Imports;

use App\Models\Pernikahan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PernikahanImporter extends Importer
{
    protected static ?string $model = Pernikahan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('no_akta')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_petugas')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('tanggal_daftar')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('nama_suami')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_istri')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_saksi1')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_saksi2')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('status_pernikahan')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('tahun_terbit')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('images')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Pernikahan
    {
        // return Pernikahan::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Pernikahan();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your pernikahan import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
