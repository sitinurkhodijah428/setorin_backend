<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BankSampahResource\Pages;
use App\Models\BankSampah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BankSampahResource extends Resource
{
    protected static ?string $model = BankSampah::class;
    protected static ?string $pluralModelLabel = 'Bank Sampah';
    protected static ?string $modelLabel = 'Bank Sampah';
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Bank Sampah';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'nama_bank';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Bank Sampah')
                    ->description('Kelola informasi bank sampah.')
                    ->schema([
                        Forms\Components\TextInput::make('nama_bank')
                            ->label('Nama Bank Sampah')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Bank Sampah Sejahtera'),

                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('no_telepon')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->placeholder('08xxxxxxxxxx'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aktif' => 'Aktif',
                                'nonaktif' => 'Nonaktif',
                            ])
                            ->default('aktif')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_bank')
                    ->label('Nama Bank Sampah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->alamat),

                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('Telepon')
                    ->copyable(),

                Tables\Columns\TextColumn::make('nasabah_count')
                    ->counts('nasabah')
                    ->label('Jml Nasabah')
                    ->sortable(),

                Tables\Columns\TextColumn::make('petugas_count')
                    ->counts('petugas')
                    ->label('Jml Petugas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'nonaktif' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'aktif' => 'Aktif',
                        'nonaktif' => 'Nonaktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggle')
                    ->label(fn ($record) => $record->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan')
                    ->color(fn ($record) => $record->status === 'aktif' ? 'danger' : 'success')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => $record->status === 'aktif' ? 'nonaktif' : 'aktif'
                    ])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankSampahs::route('/'),
            'create' => Pages\CreateBankSampah::route('/create'),
            'edit' => Pages\EditBankSampah::route('/{record}/edit'),
        ];
    }
}
