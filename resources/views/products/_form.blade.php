<div class="grid gap-6 md:grid-cols-2">
    <div>
        <x-label for="sku" value="SKU" />
        <x-input id="sku" name="sku" type="text" class="mt-1 block w-full" value="{{ old('sku', $product->sku) }}" required autofocus />
        <x-input-error for="sku" class="mt-2" />
    </div>

    <div>
        <x-label for="barcode" value="Barcode" />
        <x-input id="barcode" name="barcode" type="text" class="mt-1 block w-full" value="{{ old('barcode', $product->barcode) }}" />
        <x-input-error for="barcode" class="mt-2" />
    </div>

    <div>
        <x-label for="name" value="Name" />
        <x-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $product->name) }}" required />
        <x-input-error for="name" class="mt-2" />
    </div>

    <div>
        <x-label for="uom" value="Unit of measure" />
        <x-input id="uom" name="uom" type="text" class="mt-1 block w-full" value="{{ old('uom', $product->uom ?: 'pcs') }}" required />
        <x-input-error for="uom" class="mt-2" />
    </div>

    <div>
        <x-label for="category" value="Category" />
        <x-input id="category" name="category" type="text" class="mt-1 block w-full" value="{{ old('category', $product->category) }}" />
        <x-input-error for="category" class="mt-2" />
    </div>

    <div>
        <x-label for="supplier" value="Supplier" />
        <x-input id="supplier" name="supplier" type="text" class="mt-1 block w-full" value="{{ old('supplier', $product->supplier) }}" />
        <x-input-error for="supplier" class="mt-2" />
    </div>

    <div>
        <x-label for="quantity" value="Quantity" />
        <x-input id="quantity" name="quantity" type="number" min="0" step="1" class="mt-1 block w-full" value="{{ old('quantity', $product->quantity ?? 0) }}" required />
        <x-input-error for="quantity" class="mt-2" />
    </div>

    <div>
        <x-label for="reorder_level" value="Reorder level" />
        <x-input id="reorder_level" name="reorder_level" type="number" min="0" step="1" class="mt-1 block w-full" value="{{ old('reorder_level', $product->reorder_level ?? 10) }}" required />
        <x-input-error for="reorder_level" class="mt-2" />
    </div>

    <div>
        <x-label for="unit_price" value="Unit price" />
        <x-input id="unit_price" name="unit_price" type="number" min="0" step="0.01" class="mt-1 block w-full" value="{{ old('unit_price', $product->unit_price ?? 0) }}" required />
        <x-input-error for="unit_price" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-label for="description" value="Description" />
        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
        <x-input-error for="description" class="mt-2" />
    </div>
</div>
