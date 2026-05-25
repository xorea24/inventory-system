<div class="space-y-6">
    <div>
        <x-label for="name" value="Name" />
        <x-input
            id="name"
            name="name"
            type="text"
            class="mt-1 block w-full"
            value="{{ old('name', $warehouse->name) }}"
            required
            autofocus
        />
        <x-input-error for="name" class="mt-2" />
    </div>

    <div>
        <x-label for="address" value="Address" />
        <textarea
            id="address"
            name="address"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old('address', $warehouse->address) }}</textarea>
        <x-input-error for="address" class="mt-2" />
    </div>

    <div>
        <x-label for="country" value="Country" />
        <x-input
            id="country"
            name="country"
            type="text"
            class="mt-1 block w-full"
            value="{{ old('country', $warehouse->country) }}"
        />
        <x-input-error for="country" class="mt-2" />
    </div>
</div>
