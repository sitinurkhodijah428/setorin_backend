<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\PenarikanSaldoResource;
use App\Models\BankSampah;
use App\Models\PenarikanSaldo;
use App\Models\TransaksiPenyetoran;
use App\Models\User;
use App\Support\Format;
use Filament\Widgets\Widget;

class StatsOverview extends Widget
{
    protected static string $view = 'filament.admin.widgets.stats-overview';
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $bulanIni  = [now()->startOfMonth(), now()];
        $lastStart = now()->subMonthNoOverflow()->startOfMonth();
        $bulanLalu = [$lastStart, $lastStart->copy()->endOfMonth()];

        $trx = fn (array $r) => TransaksiPenyetoran::whereBetween('created_at', $r);
        $selesai = fn (array $r) => $trx($r)->where('status', 'selesai');
        $nasabahBaru = fn (array $r) => User::role('nasabah')->whereBetween('created_at', $r);

        $pending = PenarikanSaldo::where('status', 'pending')->count();

        return ['stats' => [
            [
                'icon' => 'heroicon-o-users', 'tone' => 'green',
                'value' => number_format(User::role('nasabah')->where('status', 'aktif')->count(), 0, ',', '.'),
                'label' => 'Total Nasabah',
                'trend' => Format::trend($nasabahBaru($bulanIni)->count(), $nasabahBaru($bulanLalu)->count()),
            ],
            [
                'icon' => 'heroicon-o-building-storefront', 'tone' => 'blue',
                'value' => number_format(BankSampah::where('status', 'aktif')->count(), 0, ',', '.'),
                'label' => 'Bank Sampah Aktif',
                'trend' => null,
            ],
            [
                'icon' => 'heroicon-o-banknotes', 'tone' => 'lime',
                'value' => number_format($trx($bulanIni)->count(), 0, ',', '.'),
                'label' => 'Transaksi Bulan Ini',
                'trend' => Format::trend($trx($bulanIni)->count(), $trx($bulanLalu)->count()),
            ],
            [
                'icon' => 'heroicon-o-arrow-path', 'tone' => 'green',
                'value' => Format::berat((float) TransaksiPenyetoran::where('status', 'selesai')->sum('total_berat')),
                'label' => 'Sampah Terkumpul',
                'trend' => Format::trend(
                    (float) $selesai($bulanIni)->sum('total_berat'),
                    (float) $selesai($bulanLalu)->sum('total_berat'),
                ),
            ],
            [
                'icon' => 'heroicon-o-clock', 'tone' => 'amber',
                'value' => number_format($pending, 0, ',', '.'),
                'label' => 'Penarikan Pending',
                'trend' => null,
                'url' => PenarikanSaldoResource::getUrl(),
            ],
        ]];
    }
}