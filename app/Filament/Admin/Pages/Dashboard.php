<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Widgets\KomposisiSampah;
use App\Filament\Admin\Widgets\StatsOverview;
use App\Filament\Admin\Widgets\TransaksiChart;
use App\Filament\Admin\Widgets\TransaksiTerbaru;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon  = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title           = 'Dashboard';
    protected static ?int $navigationSort     = -2;

    public function getHeading(): string|Htmlable
    {
        return 'Selamat datang, ' . (auth()->user()->nama ?? 'Admin') . ' 👋';
    }

    public function getSubheading(): ?string
    {
        return Carbon::now()->locale('id')->translatedFormat('l, d F Y')
            . ' · Ringkasan operasional hari ini';
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,     // baris 1: kartu statistik (full)
            TransaksiChart::class,    // baris 2: grafik (2 kolom)
            KomposisiSampah::class,   // baris 2: komposisi (1 kolom)
            TransaksiTerbaru::class,  // baris 3: tabel (full)
        ];
    }

    public function getColumns(): int|string|array
    {
        return ['default' => 1, 'lg' => 3];
    }
}