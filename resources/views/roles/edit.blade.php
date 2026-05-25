<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Permissions') }}: {{ $role->name }}
            </h2>

            <a href="{{ route('roles.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Back to roles
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-md bg-red-50 p-4 text-sm font-medium text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <x-validation-errors class="mb-6" />

            <form method="POST" action="{{ route('roles.permissions.update', $role) }}"
                class="bg-white shadow-xl sm:rounded-lg">
                @csrf
                @method('PUT')

                <div class="border-b border-gray-200 px-6 py-5">
                    <p class="text-sm text-gray-600">
                        Select the permissions this role should have. Changes apply to every user assigned to this role.
                    </p>
                </div>

                <div class="grid gap-6 p-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($permissions as $group => $groupPermissions)
                        <section class="rounded-md border border-gray-200">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                                <h3 class="text-sm font-semibold text-gray-900">{{ $group }}</h3>
                            </div>

                            <div class="space-y-3 p-4">
                                @foreach ($groupPermissions as $permission)
                                    <label class="flex items-start gap-3 text-sm text-gray-700">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                            @checked($role->hasPermissionTo($permission->name))
                                            class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span>
                                            <span class="block font-medium text-gray-900">{{ $permission->name }}</span>
                                            <span
                                                class="block text-xs text-gray-500">{{ str($permission->name)->after('.')->headline() }}</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <a href="{{ route('roles.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>

                    <x-button>
                        Save permissions
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>