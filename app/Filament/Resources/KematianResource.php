<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Kematian;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\KematianExporter;
use App\Filament\Imports\KematianImporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Resources\KematianResource\Pages;

class KematianResource extends Resource
{
    protected static ?string $model = Kematian::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-x-mark';
    protected static ?string $navigationGroup = 'Management Dokumen';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Akta Kematian';
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
                    ->default(now()),
                Forms\Components\TextInput::make('nama_Almarhum')
                    ->required()
                    ->label('Nama Almarhum')
                    ->placeholder('Masukkan Nama Almarhum')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_kematian')
                    ->required()
                    ->label('Tanggal Kematian'),
                Forms\Components\DatePicker::make('tahun_terbit')
                    ->required()
                    ->label('Tahun Penerbitan')
                    ->default(now()->format('Y')),
                Forms\Components\TextInput::make('tempat_kematian')
                    ->required()
                    ->label('Tempat Kematian')
                    ->placeholder('Masukkan Tempat Kematian')
                    ->default('Magetan')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('images')
                    ->multiple() // Mengizinkan multi-upload
                    ->image()
                    ->directory('kematians')
                    ->disk('public')
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxSize(5 * 1024 * 1024)
                    ->label('Upload Dokumen')
                    ->getUploadedFileNameForStorageUsing(function ($file): string {
                        $timestamp = now()->format('Y-m-d_H-i-s'); // Format waktu sesuai kebutuhan
                        $extension = $file->getClientOriginalExtension();

                        // Nama file dibuat sesuai tabel kelahiran, diikuti dengan waktu dan ekstensi asli
                        return "kematian_{$timestamp}.{$extension}";
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_akta')
                    ->searchable()
                    ->label('Nomor Akta'),
                Tables\Columns\TextColumn::make('nama_petugas')
                    ->searchable()
                    ->label('Nama Petugas')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tanggal_daftar')
                    ->date()
                    ->sortable()
                    ->searchable()
                    ->label('Tanggal Penaftaran')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d M')),
                Tables\Columns\TextColumn::make('nama_Almarhum')
                    ->searchable()
                    ->label('Nama Almarhum'),
                Tables\Columns\TextColumn::make('tanggal_kematian')
                    ->date()
                    ->searchable()
                    ->label('Tanggal Kematian')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_terbit')
                    ->sortable()
                    ->searchable()
                    ->label('Tahun Penerbitan')
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y')),
                Tables\Columns\TextColumn::make('tempat_kematian')
                    ->searchable()
                    ->label('Tempat Kematian')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('images')
                    ->label('Dokumen')
                    ->square()
                    ->stacked()
                    ->size(40)
                    ->limit(1)
                    ->limitedRemainingText(size: 'lg')
                    ->extraImgAttributes(['loading' => 'lazy']),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus pada')
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
                            'resource' => 'Kematian', // Ganti dengan nama resource yang sesuai
                            'id' => $record->id
                        ])), // Membuka unduhan di tab baru
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make('xlsx')
                        ->exporter(KematianExporter::class)
                        ->label('Export Excel')
                        ->formats([ExportFormat::Xlsx])
                ]),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make('xlsx')
                    ->exporter(KematianExporter::class)
                    ->label('Export Excel')
                    ->formats([ExportFormat::Xlsx]),
                Tables\Actions\ImportAction::make()
                    ->importer(KematianImporter::class)
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
            'index' => Pages\ListKematians::route('/'),
            'create' => Pages\CreateKematian::route('/create'),
        ];
    }
}
