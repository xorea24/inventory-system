<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Bins') }}
            </h2>

            @can('warehouse.manage')
                <a href="{{ route('bins.create') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                    Add Bin
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Bin</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Barcode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aisle</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Zone</th>
                                @can('warehouse.manage')
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($bins as $bin)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $bin->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono text-xs">{{ $bin->barcode }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        <a href="{{ route('aisles.show', $bin->aisle) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $bin->aisle->name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        <a href="{{ route('zones.show', $bin->aisle->zone) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $bin->aisle->zone->name }}
                                        </a>
                                    </td>
                                    @can('warehouse.manage')
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <div class="flex justify-end gap-4">
                                                <a href="{{ route('bins.barcode', $bin) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">Barcode</a>
                                                <a href="{{ route('bins.edit', $bin) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form method="POST" action="{{ route('bins.destroy', $bin) }}" onsubmit="return confirm('Delete this bin?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No bins found.
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
