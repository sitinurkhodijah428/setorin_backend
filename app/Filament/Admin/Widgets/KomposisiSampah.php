<?php

namespace App\Filament\Admin\Widgets;

use App\Support\Format;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class KomposisiSampah extends Widget
{
    protected static string $view = 'filament.admin.widgets.komposisi-sampah';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = ['default' => 1, 'lg' => 1];

    protected function getViewData(): array
    {
        $rows = DB::table('detail_transaksi_sampah as d')
            ->join('transaksi_penyetoran as t', 't.id', '=', 'd.id_transaksi')
            ->join('harga_sampah as h', 'h.id', '=', 'd.id_harga_sampah')
            ->where('t.status', 'selesai')
            ->selectRaw('h.jenis_sampah as jenis, SUM(d.berat_kg) as berat')
            ->groupBy('h.jenis_sampah')
            ->orderByDesc('berat')
            ->limit(5)
            ->get();

        $warna = ['#14532d', '#2563eb', '#ea580c', '#7c3aed', '#84cc16'];

        if ($rows->isEmpty()) {
            return [
                'items' => collect([
                    ['jenis' => 'Plastik', 'berat' => '8,4 ton', 'persen' => 90, 'warna' => '#14532d'],
                    ['jenis' => 'Kertas',  'berat' => '6,7 ton', 'persen' => 72, 'warna' => '#2563eb'],
                    ['jenis' => 'Logam',   'berat' => '4,2 ton', 'persen' => 45, 'warna' => '#ea580c'],
                    ['jenis' => 'Kaca',    'berat' => '3,1 ton', 'persen' => 33, 'warna' => '#7c3aed'],
                    ['jenis' => 'Organik', 'berat' => '2,4 ton', 'persen' => 26, 'warna' => '#84cc16'],
                ]),
            ];
        }

        $max = max((float) $rows->max('berat'), 1);

        return [
            'items' => $rows->values()->map(fn ($r, $i) => [
                'jenis'  => $r->jenis,
                'berat'  => Format::berat((float) $r->berat),
                'persen' => round(($r->berat / $max) * 100),
                'warna'  => $warna[$i % count($warna)],
            ]),
        ];
    }
}