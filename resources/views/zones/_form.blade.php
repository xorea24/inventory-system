<div class="space-y-6">
    <div>
        <x-label for="warehouse_id" value="Warehouse" />
        <select id="warehouse_id" name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Select warehouse</option>
            @foreach ($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" @selected((int) old('warehouse_id', $zone->warehouse_id) === $warehouse->id)>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
        <x-input-error for="warehouse_id" class="mt-2" />
    </div>

    <div>
        <x-label for="name" value="Zone name" />
        <x-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $zone->name) }}" required autofocus />
        <x-input-error for="name" class="mt-2" />
    </div>
</div>
