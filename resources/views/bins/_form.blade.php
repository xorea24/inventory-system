<div class="space-y-6">
    <div>
        <x-label for="aisle_id" value="Aisle" />
        <select id="aisle_id" name="aisle_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Select aisle</option>
            @foreach ($aisles as $aisle)
                <option value="{{ $aisle->id }}" @selected((int) old('aisle_id', $bin->aisle_id) === $aisle->id)>
                    {{ $aisle->zone->warehouse->name }} - {{ $aisle->zone->name }} - {{ $aisle->name }}
                </option>
            @endforeach
        </select>
        <x-input-error for="aisle_id" class="mt-2" />
    </div>

    <div>
        <x-label for="name" value="Bin name" />
        <x-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $bin->name) }}" required autofocus />
        <x-input-error for="name" class="mt-2" />
    </div>

    @if ($bin->exists)
        <div>
            <x-label for="barcode" value="Barcode (Read-only)" />
            <x-input id="barcode" type="text" class="mt-1 block w-full bg-gray-50" value="{{ $bin->barcode }}" readonly />
        </div>
    @endif
</div>
