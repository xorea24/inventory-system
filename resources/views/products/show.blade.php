<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Product') }}: {{ $product->name }}
            </h2>

            <div class="flex gap-4">
                <a href="{{ route('products.index') }}" class="inline-flex items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Back to list
                </a>
                @can('product.edit')
                    <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                        Edit product
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-md bg-green-50 p-4 text-sm font-medium text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">SKU</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $product->sku }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Barcode</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $product->barcode ?: 'Not set' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">UOM</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $product->uom }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Category</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $product->category ?: 'Not set' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Quantity</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($product->quantity) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Reorder level</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($product->reorder_level) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Unit price</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($product->unit_price, 2) }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Supplier</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $product->supplier ?: 'Not set' }}</p>
                    </div>
                </div>

                @if ($product->description)
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Description</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $product->description }}</p>
                    </div>
                @endif
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex items-center justify-between border-b border-gray-200 p-6">
                    <h3 class="text-lg font-medium text-gray-900">Variants</h3>
                    @can('product.create')
                        <a href="{{ route('products.variants.create', $product) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                            + Add variant
                        </a>
                    @endcan
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">SKU</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Barcode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">UOM</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Unit price</th>
                                @canany(['product.edit', 'product.delete'])
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($variants as $variant)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $variant->sku }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $variant->barcode ?: 'Not set' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $variant->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $variant->uom }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-600">{{ number_format($variant->quantity) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-600">{{ number_format($variant->unit_price, 2) }}</td>
                                    @canany(['product.edit', 'product.delete'])
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                            <div class="flex justify-end gap-4">
                                                @can('product.edit')
                                                    <a href="{{ route('products.variants.edit', [$product, $variant]) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        Edit
                                                    </a>
                                                @endcan

                                                @can('product.delete')
                                                    <form method="POST" action="{{ route('products.variants.destroy', [$product, $variant]) }}" onsubmit="return confirm('Delete this variant?')">
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
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No variants found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($variants->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $variants->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
