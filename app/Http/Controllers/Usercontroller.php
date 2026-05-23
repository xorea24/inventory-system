<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Models\Activity;

class UserController extends Controller
{
    // ─── List all users ───────────────────────────────────────────────────
    public function index()
    {
        $this->authorize('user.view');

        $users = User::with('roles')
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    // ─── Show create form ─────────────────────────────────────────────────
    public function create()
    {
        $this->authorize('user.create');

        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // ─── Store new user ───────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->authorize('user.create');

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        // Log the event
        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties(['role' => $validated['role']])
            ->log('User created');

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} created successfully.");
    }

    // ─── Show edit form ───────────────────────────────────────────────────
    public function edit(User $user)
    {
        $this->authorize('user.edit');

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // ─── Update user ──────────────────────────────────────────────────────
    public function update(Request $request, User $user)
    {
        $this->authorize('user.edit');

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ]);

        $oldRole = $user->getRoleNames()->first();

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            ...($validated['password']
                ? ['password' => Hash::make($validated['password'])]
                : []),
        ]);

        $user->syncRoles([$validated['role']]);

        // Log the event
        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'old_role' => $oldRole,
                'new_role' => $validated['role'],
            ])
            ->log('User updated');

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} updated successfully.");
    }

    // ─── Deactivate user (soft disable — no delete) ───────────────────────
    public function deactivate(User $user)
    {
        $this->authorize('user.deactivate');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => false]);

        // Log the event
        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User deactivated');

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} has been deactivated.");
    }

    // ─── Reactivate user ──────────────────────────────────────────────────
    public function reactivate(User $user)
    {
        $this->authorize('user.edit');

        $user->update(['is_active' => true]);

        activity('user_management')
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('User reactivated');

        return redirect()->route('users.index')
            ->with('success', "User {$user->name} has been reactivated.");
    }

    // ─── View activity log for a user ─────────────────────────────────────
    public function activityLog(User $user)
    {
        $this->authorize('user.view');

        $logs = Activity::causedBy($user)
            ->latest()
            ->paginate(20);

        return view('users.activity-log', compact('user', 'logs'));
    }
}