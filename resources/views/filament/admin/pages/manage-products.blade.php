<x-filament::page>
    <div class="space-y-6">
        <x-filament::card>
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">
                    Related Products for: {{ $this->record->title }}
                </h3>
            </div>
        </x-filament::card>

        {{ $this->table }}
    </div>
</x-filament::page>
