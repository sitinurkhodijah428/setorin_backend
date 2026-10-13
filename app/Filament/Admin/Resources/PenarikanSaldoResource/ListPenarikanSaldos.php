<?php

namespace App\Filament\Admin\Resources\PenarikanSaldoResource\Pages;

use App\Filament\Admin\Resources\PenarikanSaldoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenarikanSaldos extends ListRecords
{
    protected static string $resource = PenarikanSaldoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}