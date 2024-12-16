<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionsController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('Admin.adminroles', compact('roles', 'permissions'));
    }

    public function createRole(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create($validatedData);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function assignRole(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($validatedData['user_id']);
        $user->assignRole($validatedData['role']);

        return redirect()->route('admin.roles.index')->with('success', 'Role assigned successfully.');
    }
}

