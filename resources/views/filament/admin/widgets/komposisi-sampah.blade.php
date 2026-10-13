<x-filament-widgets::widget>
    <x-filament::section heading="Komposisi Sampah">
        @forelse ($items as $item)
            <div class="sj-komp">
                <div class="sj-komp-row">
                    <span class="sj-komp-name">{{ $item['jenis'] }}</span>
                    <span class="sj-komp-val">{{ $item['berat'] }}</span>
                </div>
                <div class="sj-track">
                    <div class="sj-fill" style="width: {{ $item['persen'] }}%; background: {{ $item['warna'] }}"></div>
                </div>
            </div>
        @empty
            <p class="sj-muted">Belum ada data setoran.</p>
        @endforelse
    </x-filament::section>
</x-filament-widgets::widget>