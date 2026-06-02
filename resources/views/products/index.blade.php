<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>

            @can('product.create')
                <a href="{{ route('products.create') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                    Add product
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $productColumns = auth()->user()->canAny(['product.edit', 'product.delete']) ? 9 : 8;
            @endphp

            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 p-4 text-sm font-medium text-red-800">
                    <ul class="list-disc space-y-1 ps-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @can('product.create')
                <div class="mb-6 bg-white p-6 shadow-xl sm:rounded-lg">
                    <form method="POST" action="{{ route('products.import') }}" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                        @csrf

                        <div>
                            <x-label for="file" value="Import products" />
                            <input
                                id="file"
                                name="file"
                                type="file"
                                accept=".xlsx,.xls,.csv,.txt"
                                required
                                class="mt-1 block w-full rounded-md border border-gray-300 text-sm shadow-sm file:me-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200"
                            />
                            <p class="mt-2 text-xs text-gray-500">
                                Required headings: sku, name. Optional: description, category, quantity, reorder_level, unit_price, supplier.
                            </p>
                        </div>

                        <x-button type="submit">
                            Import
                        </x-button>
                    </form>
                </div>
            @endcan

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">SKU</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Barcode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">UOM</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Category</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Unit price</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Variants</th>
                                @canany(['product.edit', 'product.delete'])
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900">
                                            {{ $product->sku }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $product->barcode ?: 'Not set' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $product->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $product->uom }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $product->category ?: 'Not set' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-600">{{ number_format($product->quantity) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-600">{{ number_format($product->unit_price, 2) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-600">{{ number_format($product->variants_count) }}</td>
                                    @canany(['product.edit', 'product.delete'])
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <div class="flex justify-end gap-4">
                                                @can('product.edit')
                                                    <a href="{{ route('products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        Edit
                                                    </a>
                                                @endcan

                                                @can('product.delete')
                                                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product and its variants?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    @endcanany
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $productColumns }}" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
