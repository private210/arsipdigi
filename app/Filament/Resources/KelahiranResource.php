<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelahiranResource\Pages;
use App\Filament\Resources\KelahiranResource\RelationManagers;
use Filament\Tables\Filters\Filter;
use App\Models\Kelahiran;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\KelahiranExporter;
use App\Filament\Imports\KelahiranImporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables\Actions\Action;
use PhpParser\Node\Stmt\Label;

class KelahiranResource extends Resource
{
    protected static ?string $model = Kelahiran::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?string $navigationGroup = 'Management Dokumen';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Akta Kelahiran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('no_akta')
                    ->required()
                    ->placeholder('Masukkan Nomor Akta')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_petugas')
                    ->required()
                    ->placeholder('Masukkan Nama Petugas')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_daftar')
                    ->required()
                    ->default(now()),
                Forms\Components\TextInput::make('nama_anak')
                    ->required()
                    ->placeholder('Masukkan Nama Anak')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_ayah')
                    ->required()
                    ->placeholder('Masukkan Nama Ayah')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_ibu')
                    ->required()
                    ->placeholder('Masukkan Nama Ibu')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal_lahir_anak')
                    ->required(),
                Forms\Components\DatePicker::make('tahun_terbit')
                    ->required()
                    ->default(now()->format('Y')),
                Forms\Components\TextInput::make('tempat_lahir_anak')
                    ->required()
                    ->placeholder('Masukkan Tempat Lahir Anak')
                    ->maxLength(255)
                    ->default('Magetan'),
                Forms\Components\Select::make('jenis_kelamin_anak')
                    ->required()
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
                Forms\Components\Select::make('status_nikah_orangtua')
                    ->required()
                    ->options([
                        'Kawin Tercatat' => 'Kawin Tercatat',
                        'Kawin Tidak Tercatat' => 'Kawin Tidak Tercatat',
                    ])
                    ->default('Kawin Tercatat'),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->placeholder('Masukkan Alamat')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('images')
                    ->multiple() // Mengizinkan multi-upload
                    ->image()
                    ->directory('kelahirans')
                    ->disk('public')
                    ->preserveFilenames()
                    ->acceptedFileTypes(['image/*', 'application/pdf'])
                    ->maxSize(5 * 1024 * 1024)
                    ->label('Upload Dokumen')
                    ->getUploadedFileNameForStorageUsing(function ($file): string {
                        $timestamp = now()->format('Y-m-d_H-i-s'); // Format waktu sesuai kebutuhan
                        $extension = $file->getClientOriginalExtension();

                        // Nama file dibuat sesuai tabel kelahiran, diikuti dengan waktu dan ekstensi asli
                        return "kelahiran_{$timestamp}.{$extension}";
                    }),
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
                    ->searchable()
                    ->label('Tanggal Pendaftaran')
                    ->sortable()
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('d M')),
                Tables\Columns\TextColumn::make('nama_anak')
                    ->searchable()
                    ->label('Nama Anak'),
                Tables\Columns\TextColumn::make('nama_ayah')
                    ->searchable()
                    ->label('Ayah'),
                Tables\Columns\TextColumn::make('nama_ibu')
                    ->searchable()
                    ->label('Ibu'),
                Tables\Columns\TextColumn::make('tanggal_lahir_anak')
                    ->date()
                    ->sortable()
                    ->label('Tanggal Lahir'),
                Tables\Columns\TextColumn::make('tahun_terbit')
                    ->date()
                    ->searchable()
                    ->label('Tahun Penerbitan')
                    ->sortable()
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->format('Y')),
                Tables\Columns\TextColumn::make('tempat_lahir_anak')
                    ->searchable()
                    ->label('Tempat Lahir')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('jenis_kelamin_anak')
                    ->searchable()
                    ->label('Jenis Kelamin')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_nikah_orangtua')
                    ->searchable()
                    ->label('Status Nikah Orang Tua')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->label('Alamat')
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
                    Action::make('download zip')
                        ->label('Unduh Gambar dan File')
                        ->icon('heroicon-o-arrow-down')
                        ->url(fn($record) => route('download.file', [
                            'resource' => 'Kelahiran', // Ganti dengan nama resource yang sesuai
                            'id' => $record->id
                        ])), // Membuka unduhan di tab baru
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->formats([ExportFormat::Xlsx])
                ]),
            ])
            ->headerActions([
                Tables\Actions\ImportAction::make('Import')
                    ->importer(KelahiranImporter::class)
                    ->Label('Import Data'),
                Tables\Actions\ExportAction::make('Export')
                    ->exporter(KelahiranExporter::class)
                    ->label('Export Data')
                    ->formats([ExportFormat::Xlsx])
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
            'index' => Pages\ListKelahirans::route('/'),
            'create' => Pages\CreateKelahiran::route('/create'),
        ];
    }
}
