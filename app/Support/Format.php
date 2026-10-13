<?php

namespace App\Support;

class Format
{
    /** kg -> "24,8 ton" atau "850 kg" */
    public static function berat(float $kg): string
    {
        return $kg >= 1000
            ? number_format($kg / 1000, 1, ',', '.') . ' ton'
            : number_format($kg, 0, ',', '.') . ' kg';
    }

    /** 186400000 -> "Rp 186,4 jt" */
    public static function rupiah(float $n): string
    {
        return match (true) {
            $n >= 1_000_000_000 => 'Rp ' . number_format($n / 1_000_000_000, 1, ',', '.') . ' M',
            $n >= 1_000_000     => 'Rp ' . number_format($n / 1_000_000, 1, ',', '.') . ' jt',
            default             => 'Rp ' . number_format($n, 0, ',', '.'),
        };
    }

    /** Persentase perubahan bulan ini vs bulan lalu. null jika tidak bisa dibandingkan. */
    public static function trend(float $now, float $prev): ?array
    {
        if ($prev <= 0) {
            return null;
        }
        $pct = (($now - $prev) / $prev) * 100;

        return ['up' => $pct >= 0, 'label' => number_format(abs($pct), 1, ',', '.') . '%'];
    }
}