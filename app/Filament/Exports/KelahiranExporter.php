<?php

namespace App\Filament\Exports;

use App\Models\Kelahiran;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class KelahiranExporter extends Exporter
{
    protected static ?string $model = Kelahiran::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('no_akta'),
            ExportColumn::make('nama_petugas'),
            ExportColumn::make('tanggal_daftar'),
            ExportColumn::make('nama_anak'),
            ExportColumn::make('nama_ayah'),
            ExportColumn::make('nama_ibu'),
            ExportColumn::make('tanggal_lahir_anak'),
            ExportColumn::make('tahun_terbit'),
            ExportColumn::make('tempat_lahir_anak'),
            ExportColumn::make('jenis_kelamin_anak'),
            ExportColumn::make('status_nikah_orangtua'),
            ExportColumn::make('alamat'),
            ExportColumn::make('images'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your kelahiran export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
