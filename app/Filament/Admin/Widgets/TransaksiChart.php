<?php

namespace App\Filament\Admin\Widgets;

use App\Models\TransaksiPenyetoran;
use Filament\Widgets\ChartWidget;

class TransaksiChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '260px';
    protected int|string|array $columnSpan = ['default' => 1, 'lg' => 2];

    public ?string $filter = 'berat7';

    protected function getFilters(): ?array
    {
        return [
            'berat7'  => '7 hari terakhir',
            'berat30' => '30 hari terakhir',
            'trx7'    => 'Transaksi (7 hari)',
            'trx30'   => 'Transaksi (30 hari)',
        ];
    }

    private function mode(): string
    {
        return str_starts_with($this->filter ?? 'berat7', 'berat') ? 'berat' : 'trx';
    }

    private function days(): int
    {
        return (int) preg_replace('/\D/', '', $this->filter ?? 'berat7') ?: 7;
    }

    public function getHeading(): ?string
    {
        return 'Tren Setoran Sampah';
    }

    public function getDescription(): ?string
    {
        $days = $this->days();
        $isBerat = $this->mode() === 'berat';
        return $isBerat ? "Total berat {$days} hari terakhir" : "Jumlah transaksi {$days} hari terakhir";
    }

    protected function getData(): array
    {
        $days = $this->days();
        $berat = $this->mode() === 'berat';

        $perHari = TransaksiPenyetoran::query()
            ->selectRaw('DATE(created_at) as tgl, COUNT(*) as jml, SUM(total_berat_kg) as berat')
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->when($berat, fn ($q) => $q->where('status', 'selesai'))
            ->groupBy('tgl')
            ->get()
            ->keyBy('tgl');

        $hari = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        $labels = $data = $colors = [];

        // Sample fallback data jika database lokal masih kosong
        $fallback = [35, 48, 38, 70, 60, 95, 68];

        for ($i = $days - 1; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $row = $perHari->get($d->toDateString());

            $labels[] = $days <= 7 ? $hari[$d->dayOfWeekIso - 1] : $d->format('d/m');

            $val = $berat ? round((float) ($row->berat ?? 0), 1) : (int) ($row->jml ?? 0);
            if ($perHari->isEmpty()) {
                $val = $fallback[(6 - $i) % count($fallback)];
            }
            $data[] = $val;
        }

        // Tentukan nilai tertinggi untuk diberi highlight hijau tua solid
        $maxVal = max($data ?: [1]);
        foreach ($data as $v) {
            $colors[] = ($v === $maxVal && $maxVal > 0) ? '#14532d' : '#d1fae5';
        }

        return [
            'datasets' => [[
                'label' => $berat ? 'Berat (kg)' : 'Transaksi',
                'data' => $data,
                'backgroundColor' => $colors,
                'borderRadius' => 6,
                'borderSkipped' => false,
                'barPercentage' => 0.42,
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
            'plugins' => [
                'legend' => ['display' => false],
                'tooltip' => [
                    'enabled' => true,
                    'backgroundColor' => 'rgba(15, 62, 43, 0.95)',
                    'titleFont' => ['size' => 12, 'weight' => 'bold'],
                    'bodyFont' => ['size' => 12],
                    'padding' => 10,
                    'cornerRadius' => 8,
                ],
            ],
            'scales' => [
                'y' => [
                    'display' => false,
                    'grid' => ['display' => false],
                ],
                'x' => [
                    'grid' => ['display' => false],
                    'border' => ['display' => false],
                    'ticks' => [
                        'font' => ['size' => 11, 'weight' => '500'],
                        'color' => '#6b7280',
                    ],
                ],
            ],
        ];
    }
}