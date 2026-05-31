<div class="space-y-6">
    <div>
        <x-label for="name" value="Name" />
        <x-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $user->name ?? '') }}" required autofocus />
        <x-input-error for="name" class="mt-2" />
    </div>

    <div>
        <x-label for="email" value="Email" />
        <x-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $user->email ?? '') }}" required />
        <x-input-error for="email" class="mt-2" />
    </div>

    @if (!isset($user) || !$user->exists)
        <div>
            <x-label for="password" value="Password" />
            <x-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div>
            <x-label for="password_confirmation" value="Confirm Password" />
            <x-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
        </div>
    @else
        <div>
            <x-label for="password" value="Password (Leave blank to keep current)" />
            <x-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div>
            <x-label for="password_confirmation" value="Confirm Password" />
            <x-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
        </div>
    @endif

    <div>
        <x-label for="role" value="Role" />
        <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Select role</option>
            @foreach ($roles as $role)
                <option value="{{ $role->name }}" @selected(old('role', isset($user) ? $user->getRoleNames()->first() : '') === $role->name)>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        <x-input-error for="role" class="mt-2" />
    </div>

    <div>
        <x-label for="warehouse_id" value="Assigned Warehouse (Optional)" />
        <select id="warehouse_id" name="warehouse_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">No warehouse assigned</option>
            @foreach (App\Models\Warehouse::all() as $warehouse)
                <option value="{{ $warehouse->id }}" @selected((int) old('warehouse_id', $user->warehouse_id ?? '') === $warehouse->id)>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
        <x-input-error for="warehouse_id" class="mt-2" />
    </div>
</div>
