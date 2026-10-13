<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\TransaksiPenyetoranResource;
use App\Models\TransaksiPenyetoran;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class TransaksiTerbaru extends TableWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Transaksi Setoran Terbaru')
            ->description('Pembaruan otomatis dari seluruh titik layanan')
            ->query(
                TransaksiPenyetoran::query()
                    ->with(['nasabah.pengguna', 'detail.hargaSampah'])
                    ->latest()
                    ->limit(5)
            )
            ->paginated(false)
            ->poll('30s')
            ->headerActions([
                Tables\Actions\Action::make('semua')
                    ->label('Lihat semua →')
                    ->link()
                    ->url(fn () => TransaksiPenyetoranResource::getUrl()),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID TRANSAKSI')
                    ->formatStateUsing(fn ($record) => sprintf('TRX-%s-%04d', $record->created_at->format('Y'), $record->id)),
                Tables\Columns\TextColumn::make('nasabah.pengguna.nama')->label('NASABAH'),
                Tables\Columns\TextColumn::make('kategori')->label('KATEGORI')
                    ->state(fn ($record) => $record->detail->first()?->hargaSampah?->jenis_sampah ?? '-'),
                Tables\Columns\TextColumn::make('total_berat')->label('BERAT')
                    ->formatStateUsing(fn ($state) => rtrim(rtrim(number_format($state, 1, ',', '.'), '0'), ',') . ' kg'),
                Tables\Columns\TextColumn::make('total_harga')->label('NILAI TUKAR')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('status')->label('STATUS')
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => $state === 'selesai' ? 'success' : 'warning')
                    ->weight('medium'),
            ]);
    }
}