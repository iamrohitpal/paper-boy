<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        // Super Admin can see all users, tenants and their active plan / subscription days
        $users = User::with(['roles', 'tenant.subscriptions.plan'])->get();
        $plans = \App\Models\Plan::where('status', 'active')->get();

        return view('system.users.index', compact('users', 'plans'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('system.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'owner_id' => null, // Tenants are their own owners
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'Tenant/User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('system.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'roles' => 'required|array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'Tenant/User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('Super Admin')) {
            return redirect()->route('users.index')->with('error', 'Cannot delete a Super Admin.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Tenant/User deleted successfully.');
    }

    public function updateSubscription(Request $request, User $user)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'days' => 'required|integer|min:1',
        ]);

        $tenant = $user->tenant;
        if (!$tenant) {
            return back()->with('error', 'User does not belong to a tenant.');
        }

        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        // Cancel previous active subscriptions
        \App\Models\TenantSubscription::where('tenant_id', $tenant->id)->update(['status' => 'cancelled']);

        \App\Models\TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addDays((int) $request->days),
            'customer_limit_snapshot' => $plan->customer_limit,
        ]);

        return back()->with('success', "Subscription for {$user->name} updated to {$plan->name} for {$request->days} days.");
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'role_filter' => 'nullable|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $query = User::query();
        if ($request->filled('role_filter')) {
            $query->role($request->role_filter);
        }

        $users = $query->get();

        foreach ($users as $recipient) {
            $recipient->notify(new \App\Notifications\GenericSystemNotification(
                $request->title,
                $request->message
            ));
        }

        return back()->with('success', 'Notification sent to ' . $users->count() . ' user(s).');
    }
}
