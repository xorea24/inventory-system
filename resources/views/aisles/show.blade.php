<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Aisle') }}: {{ $aisle->name }} ({{ $aisle->zone->name }}, {{ $aisle->zone->warehouse->name }})
            </h2>

            <div class="flex gap-4">
                <a href="{{ route('zones.show', $aisle->zone) }}" class="inline-flex items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Back to Zone
                </a>
                @can('warehouse.manage')
                    <a href="{{ route('aisles.edit', $aisle) }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                        Edit Aisle
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Warehouse</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $aisle->zone->warehouse->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Zone</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $aisle->zone->name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Bins Count</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $aisle->bins->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Bins in this Aisle</h3>
                    @can('warehouse.manage')
                        <a href="{{ route('bins.create', ['aisle_id' => $aisle->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                            + Add Bin
                        </a>
                    @endcan
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Barcode</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($bins as $bin)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $bin->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono text-xs">{{ $bin->barcode }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end gap-4">
                                            <a href="{{ route('bins.barcode', $bin) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Barcode</a>
                                            <a href="{{ route('bins.edit', $bin) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No bins found in this aisle.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($bins->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $bins->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
