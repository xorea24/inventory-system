<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }}
            </h2>

            @can('user.create')
                <a href="{{ route('users.create') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700">
                    Add User
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $user->getRoleNames()->implode(', ') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        @if ($user->is_active)
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Active</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex justify-end gap-4">
                                            <a href="{{ route('users.activity-log', $user) }}" class="text-gray-600 hover:text-gray-900">Logs</a>
                                            @can('user.edit')
                                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @endcan
                                            @if ($user->id !== auth()->id())
                                                @if ($user->is_active)
                                                    @can('user.deactivate')
                                                        <form method="POST" action="{{ route('users.deactivate', $user) }}" onsubmit="return confirm('Deactivate this user?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Deactivate</button>
                                                        </form>
                                                    @endcan
                                                @else
                                                    @can('user.edit')
                                                        <form method="POST" action="{{ route('users.reactivate', $user) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-green-600 hover:text-green-900">Reactivate</button>
                                                        </form>
                                                    @endcan
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($users->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
