<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\PenarikanSaldoResource;
use App\Models\BankSampah;
use App\Models\PenarikanSaldo;
use App\Models\TransaksiPenyetoran;
use App\Models\User;
use App\Support\Format;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        $bulanIni  = [now()->startOfMonth(), now()];
        $lastStart = now()->subMonthNoOverflow()->startOfMonth();
        $bulanLalu = [$lastStart, $lastStart->copy()->endOfMonth()];

        $nasabahAktif = User::where('role', 'nasabah')->where('status_akun', 'aktif')->count();
        $nasabahBaruIni = User::where('role', 'nasabah')->whereBetween('created_at', $bulanIni)->count();
        $nasabahBaruLalu = User::where('role', 'nasabah')->whereBetween('created_at', $bulanLalu)->count();
        $trendNasabah = Format::trend($nasabahBaruIni, $nasabahBaruLalu);

        $bankAktif = BankSampah::where('status', 'aktif')->count();

        $trx = fn (array $r) => TransaksiPenyetoran::whereBetween('created_at', $r);
        $selesai = fn (array $r) => $trx($r)->where('status', 'selesai');

        $trxBulanIni = $trx($bulanIni)->count();
        $trxBulanLalu = $trx($bulanLalu)->count();
        $trendTrx = Format::trend($trxBulanIni, $trxBulanLalu);

        $totalBerat = (float) TransaksiPenyetoran::where('status', 'selesai')->sum('total_berat_kg');
        $beratBulanIni = (float) $selesai($bulanIni)->sum('total_berat_kg');
        $beratBulanLalu = (float) $selesai($bulanLalu)->sum('total_berat_kg');
        $trendBerat = Format::trend($beratBulanIni, $beratBulanLalu);

        $pending = PenarikanSaldo::where('status', 'pending')->count();

        return [
            Stat::make('Total Nasabah', number_format($nasabahAktif, 0, ',', '.'))
                ->description($trendNasabah ? ($trendNasabah['up'] ? "↗ +{$trendNasabah['label']}" : "↘ -{$trendNasabah['label']}") : 'Pengguna aktif')
                ->descriptionIcon($trendNasabah ? ($trendNasabah['up'] ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down') : 'heroicon-m-user-group')
                ->color($trendNasabah ? ($trendNasabah['up'] ? 'success' : 'danger') : 'success'),

            Stat::make('Bank Sampah Aktif', number_format($bankAktif, 0, ',', '.'))
                ->description('Unit aktif')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('info'),

            Stat::make('Transaksi Bulan Ini', number_format($trxBulanIni, 0, ',', '.'))
                ->description($trendTrx ? ($trendTrx['up'] ? "↗ +{$trendTrx['label']}" : "↘ -{$trendTrx['label']}") : 'Total setoran')
                ->descriptionIcon($trendTrx ? ($trendTrx['up'] ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down') : 'heroicon-m-arrow-path-rounded-square')
                ->color('primary'),

            Stat::make('Sampah Terkumpul', Format::berat($totalBerat))
                ->description($trendBerat ? ($trendBerat['up'] ? "↗ +{$trendBerat['label']}" : "↘ -{$trendBerat['label']}") : 'Daur ulang')
                ->descriptionIcon($trendBerat ? ($trendBerat['up'] ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down') : 'heroicon-m-scale')
                ->color('success'),

            Stat::make('Penarikan Pending', number_format($pending, 0, ',', '.'))
                ->description($pending > 0 ? 'Perlu tindakan' : 'Semua diproses')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($pending > 0 ? 'warning' : 'gray')
                ->url(PenarikanSaldoResource::getUrl()),
        ];
    }
}