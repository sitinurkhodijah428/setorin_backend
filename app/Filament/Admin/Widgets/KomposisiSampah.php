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
            ->join('transaksi_penyetoran as t', 't.id', '=', 'd.transaksi_penyetoran_id')
            ->join('harga_sampah as h', 'h.id', '=', 'd.harga_sampah_id')
            ->where('t.status', 'selesai')
            ->selectRaw('h.jenis_sampah as jenis, SUM(d.berat_kg) as berat')
            ->groupBy('h.jenis_sampah')
            ->orderByDesc('berat')
            ->limit(5)
            ->get();

        $max = max((float) $rows->max('berat'), 1);
        $warna = ['#1a7f4b', '#3b82b6', '#f5a524', '#8b6fb8', '#a5d65a'];

        return ['items' => $rows->values()->map(fn ($r, $i) => [
            'jenis'  => $r->jenis,
            'berat'  => Format::berat((float) $r->berat),
            'persen' => round(($r->berat / $max) * 100),
            'warna'  => $warna[$i % count($warna)],
        ])];
    }
}