<?php

namespace App\Filament\Imports;

use App\Models\Kelahiran;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class KelahiranImporter extends Importer
{
    protected static ?string $model = Kelahiran::class;

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
            ImportColumn::make('nama_anak')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_ayah')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('nama_ibu')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('tanggal_lahir_anak')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('tahun_terbit')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('tempat_lahir_anak')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('jenis_kelamin_anak')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('status_nikah_orangtua')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('alamat')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('images')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Kelahiran
    {
        // return Kelahiran::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Kelahiran();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your kelahiran import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
