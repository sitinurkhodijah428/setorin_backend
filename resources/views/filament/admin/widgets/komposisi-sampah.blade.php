<x-filament-widgets::widget>
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-100/80 dark:border-gray-800 shadow-sm h-full flex flex-col justify-between">
        <div>
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-5 tracking-tight">
                Komposisi Sampah
            </h3>

            <div class="space-y-4">
                @foreach ($items as $item)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1.5">
                            <span class="font-bold text-gray-900 dark:text-gray-100 text-[13px] sm:text-sm">
                                {{ $item['jenis'] }}
                            </span>
                            <span class="text-gray-500 dark:text-gray-400 font-medium text-[13px] sm:text-sm">
                                {{ $item['berat'] }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2 overflow-hidden">
                            <div
                                class="h-full rounded-full transition-all duration-500 ease-out"
                                style="width: {{ $item['persen'] }}%; background-color: {{ $item['warna'] }}"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-widgets::widget>