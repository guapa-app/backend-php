<x-filament-panels::page>
    <form wire:submit.prevent="submit">
        <x-filament::button type="submit" wire:loading.attr="disabled" wire:target="submit">
            <span wire:loading.remove wire:target="submit">Export</span>
            <span wire:loading wire:target="submit">
                Export
            </span>
        </x-filament::button>
    </form>
</x-filament-panels::page>
