<div class="grid gap-6 md:grid-cols-2">
    <div>
        <x-label for="sku" value="SKU" />
        <x-input id="sku" name="sku" type="text" class="mt-1 block w-full" value="{{ old('sku', $variant->sku) }}" required autofocus />
        <x-input-error for="sku" class="mt-2" />
    </div>

    <div>
        <x-label for="barcode" value="Barcode" />
        <x-input id="barcode" name="barcode" type="text" class="mt-1 block w-full" value="{{ old('barcode', $variant->barcode) }}" />
        <x-input-error for="barcode" class="mt-2" />
    </div>

    <div>
        <x-label for="name" value="Name" />
        <x-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $variant->name) }}" required />
        <x-input-error for="name" class="mt-2" />
    </div>

    <div>
        <x-label for="uom" value="Unit of measure" />
        <x-input id="uom" name="uom" type="text" class="mt-1 block w-full" value="{{ old('uom', $variant->uom ?: 'pcs') }}" required />
        <x-input-error for="uom" class="mt-2" />
    </div>

    <div>
        <x-label for="quantity" value="Quantity" />
        <x-input id="quantity" name="quantity" type="number" min="0" step="1" class="mt-1 block w-full" value="{{ old('quantity', $variant->quantity ?? 0) }}" required />
        <x-input-error for="quantity" class="mt-2" />
    </div>

    <div>
        <x-label for="unit_price" value="Unit price" />
        <x-input id="unit_price" name="unit_price" type="number" min="0" step="0.01" class="mt-1 block w-full" value="{{ old('unit_price', $variant->unit_price ?? 0) }}" required />
        <x-input-error for="unit_price" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-label for="description" value="Description" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $variant->description) }}</textarea>
        <x-input-error for="description" class="mt-2" />
    </div>
</div>
