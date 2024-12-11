<?php

namespace App\Filament\Exports;

use App\Models\Perceraian;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PerceraianExporter extends Exporter
{
    protected static ?string $model = Perceraian::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('no_akta'),
            ExportColumn::make('nama_petugas'),
            ExportColumn::make('tanggal_daftar'),
            ExportColumn::make('nama_suami'),
            ExportColumn::make('nama_istri'),
            ExportColumn::make('nama_saksi1'),
            ExportColumn::make('nama_saksi2'),
            ExportColumn::make('status_perceraian'),
            ExportColumn::make('tahun_terbit'),
            ExportColumn::make('images'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your perceraian export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
