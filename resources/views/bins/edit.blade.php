<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Bin') }}: {{ $bin->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <x-validation-errors class="mb-6" />

            <form method="POST" action="{{ route('bins.update', $bin) }}" class="bg-white shadow-xl sm:rounded-lg">
                @csrf
                @method('PUT')

                <div class="p-6">
                    @include('bins._form')
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <a href="{{ route('bins.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>
                    <x-button>
                        Update Bin
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
