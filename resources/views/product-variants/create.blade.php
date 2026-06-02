<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Variant') }}: {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-validation-errors class="mb-6" />

            <form method="POST" action="{{ route('products.variants.store', $product) }}" class="bg-white shadow-xl sm:rounded-lg">
                @csrf

                <div class="p-6">
                    @include('product-variants._form')
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <a href="{{ route('products.show', $product) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>

                    <x-button>
                        Save variant
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
