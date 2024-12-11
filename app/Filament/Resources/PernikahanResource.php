<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Pernikahan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\PernikahanExporter;
use App\Filament\Imports\PernikahanImporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\PernikahanResource\Pages;

class PernikahanResource extends Resource
{
    protected static ?string $model = Pernikahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Management Dokumen';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Akta Pernikahan';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_akta')
                    ->required()
                    ->label('Nomor Akta')
                    ->placeholder('Masukkan Nomor Akta')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_petugas')
                    ->required()
                    ->label('Nama Petugas')
                    ->placeholder('Masukkan Nama Petugas')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_daftar')
                    ->required()
                    ->label('Tanggal Pendaftaran')
                    ->default(now()->format('Y')),
                Forms\Components\TextInput::make('nama_suami')
                    ->required()
                    ->label('Nama Suami')
                    ->placeholder('Masukkan Nama Suami')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_istri')
                    ->required()
                    ->label('Nama Istri')
                    ->placeholder('Masukkan Nama Istri')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_saksi1')
                    ->required()
                    ->label('Nama Saksi 1')
                    ->placeholder('Masukkan Nama Saksi 1')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_saksi2')
                    ->required()
                    ->label('Nama Saksi 2')
                    ->placeholder('Masukkan Nama Saksi 2')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status_pernikahan')
                    ->required()
                    ->label('Status Pernikahan')
                    ->placeholder('Masukkan Status Pernikahan')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tahun_terbit')
                    ->required()
                    ->label('Tahun Penerbitan')
                    ->default(now()->format('Y')),
                Forms\Components\FileUpload::make('images')
                    ->multiple() // Mengizinkan multi-upload
                    ->image()
                    ->directory('pernikahans')
                    ->disk('public')
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxSize(5 * 1024 * 1024)
                    ->label('Upload Dokumen')
                    ->getUploadedFileNameForStorageUsing(function ($file): string {
                        $timestamp = now()->format('Y-m-d_H-i-s'); // Format waktu sesuai kebutuhan
                        $extension = $file->getClientOriginalExtension();

                        // Nama file dibuat sesuai tabel kelahiran, diikuti dengan waktu dan ekstensi asli
                        return "pernikahan_{$timestamp}.{$extension}";
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_akta')
                    ->searchable()
                    ->sortable()
                    ->label('Nomor Akta'),
                Tables\Columns\TextColumn::make('nama_petugas')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Petugas')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_daftar')
                    ->date()
                    ->searchable()
                    ->label('Tanggal Pendaftaran')
                    ->sortable()
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d M')),
                Tables\Columns\TextColumn::make('nama_suami')
                    ->searchable()
                    ->label('Nama Suami'),
                Tables\Columns\TextColumn::make('nama_istri')
                    ->searchable()
                    ->label('Nama Istri'),
                Tables\Columns\TextColumn::make('nama_saksi1')
                    ->searchable()
                    ->label('Nama Saksi 1')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_saksi2')
                    ->searchable()
                    ->label('Nama Saksi 2')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_pernikahan')
                    ->searchable()
                    ->label('Status Pernikahan'),
                Tables\Columns\TextColumn::make('tahun_terbit')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->label('Tahun Penerbitan')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y')),
                Tables\Columns\ImageColumn::make('images')
                    ->label('Dokumen')
                    ->square()
                    ->stacked()
                    ->size(40)
                    ->limit(1)
                    ->limitedRemainingText(size: 'lg')
                    ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                Filter::make('tahun_terbit')
                    ->form([
                        DatePicker::make('mulai_tahun_terbit'),
                        DatePicker::make('sampai_tahun_terbit'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['mulai_tahun_terbit'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tahun_terbit', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tahun_terbit'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tahun_terbit', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['mulai_tahun_terbit'] ?? null) {
                            $indicators[] = Indicator::make('Mulai Tahun Terbit ' . Carbon::parse($data['mulai_tahun_terbit'])->toFormattedDateString())
                                ->removeField('mulai_tahun_terbit');
                        }

                        if ($data['sampai_tahun_terbit'] ?? null) {
                            $indicators[] = Indicator::make('Sampai Tahun Terbit' . Carbon::parse($data['sampai_tahun_terbit'])->toFormattedDateString())
                                ->removeField('sampai_tahun_terbit');
                        }

                        return $indicators;
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Action::make('download gambar')
                        ->label('Unduh Gambar Saja')
                        ->icon('heroicon-o-arrow-down')
                        ->url(fn($record) => route('download.images', ['id' => $record->id])), // Membuka unduhan di tab baru
                    Action::make('download zip')
                        ->label('Unduh Gambar dan File')
                        ->icon('heroicon-o-arrow-down')
                        ->url(fn($record) => route('download.file', [
                            'resource' => 'Pernikahan', // Ganti dengan nama resource yang sesuai
                            'id' => $record->id
                        ])), // Membuka unduhan di tab baru
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make('xlsx')
                        ->exporter(PernikahanExporter::class)
                        ->label('Export Excel')
                        ->formats([ExportFormat::Xlsx])
                ]),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make('xlsx')
                    ->exporter(PernikahanExporter::class)
                    ->label('Export Excel')
                    ->formats([ExportFormat::Xlsx]),
                Tables\Actions\ImportAction::make()
                    ->importer(PernikahanImporter::class)
                    ->label('Import Data')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPernikahans::route('/'),
            'create' => Pages\CreatePernikahan::route('/create'),
        ];
    }
}
