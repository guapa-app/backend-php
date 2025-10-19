<x-filament::widget>
    <x-filament::card>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium">Contract</h2>
            @if($this->getContractUrl())
                <x-filament::button
                    color="gray"
                    icon="heroicon-m-document"
                    icon-alias="panels::widgets.account.logout-button"
                    labeled-from="sm"
                    tag="a"
                    href="{{ $this->getContractUrl() }}"
                    target="_blank"
                    download
                >
                    Download Contract
                </x-filament::button>
            @else
                <p class="text-sm text-gray-600">No contract file available.</p>
            @endif
        </div>
    </x-filament::card>
</x-filament::widget>
