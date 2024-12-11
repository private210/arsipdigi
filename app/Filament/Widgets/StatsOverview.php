<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;  // Gunakan Stat, bukan Card
use App\Models\Kelahiran;
use App\Models\Kematian;
use App\Models\Pernikahan;
use App\Models\Perceraian;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Jumlah Data Akta Kelahiran', Kelahiran::count()),
            Stat::make('Jumlah Data Akta Kematian', Kematian::count()),
            Stat::make('Jumlah Data Akta Pernikahan', Pernikahan::count()),
            Stat::make('Jumlah Data Akta Perceraian', Perceraian::count()),


        ];
    }
}
