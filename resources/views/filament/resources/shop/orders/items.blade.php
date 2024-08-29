<!-- resources/views/filament/resources/order-items-table.blade.php -->
<table class="w-full bg-white">
    <thead>
    <tr>
        <th class="border px-4 py-2">Item</th>
        <th class="border px-4 py-2">Quantity</th>
        <th class="border px-4 py-2">Unit Price</th>
        <th class="border px-4 py-2">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr class="text-center">
            <td class="border px-4 py-2 ">{{ $item->product->title }}</td>
            <td class="border px-4 py-2">{{ $item->quantity }}</td>
            <td class="border px-4 py-2">{{ $item->amount }}</td>
            <td class="border px-4 py-2">{{ $item->quantity * $item->amount }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
