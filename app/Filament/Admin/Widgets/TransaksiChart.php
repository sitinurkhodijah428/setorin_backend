<?php

namespace App\Filament\Admin\Widgets;

use App\Models\TransaksiPenyetoran;
use Filament\Widgets\ChartWidget;

class TransaksiChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '240px';
    protected int|string|array $columnSpan = ['default' => 1, 'lg' => 2];

    public ?string $filter = 'trx7';

    protected function getFilters(): ?array
    {
        return [
            'trx7'    => 'Jumlah transaksi · 7 hari',
            'berat7'  => 'Berat sampah · 7 hari',
            'trx30'   => 'Jumlah transaksi · 30 hari',
            'berat30' => 'Berat sampah · 30 hari',
        ];
    }

    private function mode(): string
    {
        return str_starts_with($this->filter ?? 'trx7', 'berat') ? 'berat' : 'trx';
    }

    private function days(): int
    {
        return (int) preg_replace('/\D/', '', $this->filter ?? 'trx7') ?: 7;
    }

    public function getHeading(): ?string
    {
        return $this->mode() === 'berat' ? 'Tren Berat Setoran' : 'Tren Transaksi Setoran';
    }

    public function getDescription(): ?string
    {
        return ($this->mode() === 'berat' ? 'Total berat (kg), ' : 'Jumlah transaksi, ')
            . $this->days() . ' hari terakhir';
    }

    protected function getData(): array
    {
        $days = $this->days();
        $berat = $this->mode() === 'berat';

        $perHari = TransaksiPenyetoran::query()
            ->selectRaw('DATE(created_at) as tgl, COUNT(*) as jml, SUM(total_berat) as berat')
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->when($berat, fn ($q) => $q->where('status', 'selesai'))
            ->groupBy('tgl')
            ->get()
            ->keyBy('tgl');

        $hari = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $labels = $data = $colors = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $row = $perHari->get($d->toDateString());

            $labels[] = $days <= 7 ? $hari[$d->dayOfWeekIso - 1] : $d->format('d/m');
            $data[]   = $berat ? round((float) ($row->berat ?? 0), 1) : (int) ($row->jml ?? 0);
            $colors[] = $i === 0 ? '#1a7f4b' : '#e0f0e4'; // hari ini disorot
        }

        return [
            'datasets' => [[
                'label' => $berat ? 'Berat (kg)' : 'Transaksi',
                'data' => $data,
                'backgroundColor' => $colors,
                'borderRadius' => 8,
                'borderSkipped' => false,
                'barPercentage' => 0.6,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => [
                'y' => ['display' => false, 'grid' => ['display' => false]],
                'x' => ['grid' => ['display' => false], 'border' => ['display' => false]],
            ],
        ];
    }
}