{{-- resources/views/filament/actions/manage-products.blade.php --}}
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">
                Current Products ({{ $existingProducts->count() }})
            </h3>
        </div>
        <div class="flex space-x-3">
            <x-filament::button
                type="button"
                color="primary"
                size="sm"
                wire:click="$set('activeTab', 'add-products')"
            >
                Add Products
            </x-filament::button>
        </div>
    </div>

    <div class="overflow-hidden bg-white shadow sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($existingProducts as $product)
                <li class="flex items-center justify-between px-4 py-4 sm:px-6">
                    <div class="flex items-center">
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $product->title }}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button
                            type="button"
                            wire:click="removeProduct({{ $product->id }})"
                            class="inline-flex items-center text-sm text-red-600 hover:text-red-900"
                        >
                            <x-heroicon-m-trash class="w-5 h-5" />
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
