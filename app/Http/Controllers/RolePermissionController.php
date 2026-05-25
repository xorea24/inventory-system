<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionController extends Controller
{
    public function index(): View
    {
        $this->authorize('settings.manage');

        $roles = Role::query()
            ->withCount('permissions')
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        return view('roles.index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        $this->authorize('settings.manage');

        $role->load('permissions');

        $permissions = Permission::query()
            ->where('guard_name', $role->guard_name)
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission) => str($permission->name)->before('.')->headline()->toString());

        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $this->authorize('settings.manage');

        $validated = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissions = Permission::query()
            ->where('guard_name', $role->guard_name)
            ->whereIn('id', $validated['permissions'] ?? [])
            ->pluck('name')
            ->all();

        if ($request->user()->hasRole($role) && ! in_array('settings.manage', $permissions, true)) {
            return back()
                ->withInput()
                ->with('error', 'You cannot remove settings.manage from your own role.');
        }

        $oldPermissions = $role->permissions()->pluck('name')->all();

        $role->syncPermissions($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        activity('role_management')
            ->causedBy($request->user())
            ->performedOn($role)
            ->withProperties([
                'old_permissions' => $oldPermissions,
                'new_permissions' => $permissions,
            ])
            ->log('Role permissions updated');

        return redirect()
            ->route('roles.index')
            ->with('success', "Permissions for {$role->name} updated successfully.");
    }
}
