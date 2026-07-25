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
        $ownerId = auth()->user()->owner_id ?? auth()->id();
        $users = User::where('owner_id', $ownerId)->with('roles')->get();
        return view('system.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('system.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'owner_id' => auth()->user()->owner_id ?? auth()->id(),
        ]);

        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $ownerId = auth()->user()->owner_id ?? auth()->id();
        if ($user->owner_id !== $ownerId) abort(403);

        $roles = Role::where('name', '!=', 'Super Admin')->get();
        $userRoles = $user->roles->pluck('name')->toArray();
        
        return view('system.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $ownerId = auth()->user()->owner_id ?? auth()->id();
        if ($user->owner_id !== $ownerId) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'roles' => 'required|array'
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

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $ownerId = auth()->user()->owner_id ?? auth()->id();
        if ($user->owner_id !== $ownerId) abort(403);
        
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
