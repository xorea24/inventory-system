<div class="space-y-6">
    <div>
        <x-label for="zone_id" value="Zone" />
        <select id="zone_id" name="zone_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Select zone</option>
            @foreach ($zones as $zone)
                <option value="{{ $zone->id }}" @selected((int) old('zone_id', $aisle->zone_id) === $zone->id)>
                    {{ $zone->warehouse->name }} - {{ $zone->name }}
                </option>
            @endforeach
        </select>
        <x-input-error for="zone_id" class="mt-2" />
    </div>

    <div>
        <x-label for="name" value="Aisle name" />
        <x-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $aisle->name) }}" required autofocus />
        <x-input-error for="name" class="mt-2" />
    </div>
</div>
