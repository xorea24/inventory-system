<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Zone') }}: {{ $zone->name }} ({{ $zone->warehouse->name }})
            </h2>

            <div class="flex gap-4">
                <a href="{{ route('warehouses.show', $zone->warehouse) }}" class="inline-flex items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Back to Warehouse
                </a>
                @can('warehouse.manage')
                    <a href="{{ route('zones.edit', $zone) }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                        Edit Zone
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Warehouse</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $zone->warehouse->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Aisles Count</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $zone->aisles->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Aisles in this Zone</h3>
                    @can('warehouse.manage')
                        <a href="{{ route('aisles.create', ['zone_id' => $zone->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                            + Add Aisle
                        </a>
                    @endcan
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Bins Count</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($aisles as $aisle)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        <a href="{{ route('aisles.show', $aisle) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $aisle->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $aisle->bins->count() }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end gap-4">
                                            <a href="{{ route('aisles.edit', $aisle) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No aisles found in this zone.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($aisles->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $aisles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
