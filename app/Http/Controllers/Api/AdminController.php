<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Requests\UpdateRolePermissionsRequest;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::with('role.permissions')->get();
        return response()->json($users);
    }

    public function changeRole(UpdateUserRoleRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if user is ADMIN (Admin cannot be modified this way)
        if ($user->hasRole('ADMIN')) {
            return response()->json(['message' => 'Main Admin role cannot be modified'], 403);
        }

        $roleName = $request->role; // This is receiving the role name/slug
        $slug = \Illuminate\Support\Str::slug($roleName);
        
        if ($slug === 'admin') {
             return response()->json(['message' => 'Cannot assign ADMIN role'], 403);
        }

        $role = Role::firstOrCreate(
            ['slug' => $slug],
            ['name' => $roleName]
        );

        // If permissions are provided, sync them to the role
        if ($request->has('permissions')) {
             $permissionIds = Permission::whereIn('slug', $request->permissions)->pluck('id');
             $role->permissions()->sync($permissionIds);
        }

        $user->role()->associate($role);
        $user->save();

        return response()->json(['message' => 'User role updated successfully', 'user' => $user->load('role.permissions')]);
    }

    public function roles()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function updatePermissions(UpdateRolePermissionsRequest $request, $id)
    {
        $targetRole = Role::find($id);

        if (!$targetRole) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Check if role is ADMIN
        if ($targetRole->slug === 'ADMIN') {
            return response()->json(['message' => 'Admin permissions cannot be modified'], 403);
        }

        $permissionIds = Permission::whereIn('slug', $request->permissions ?? [])->pluck('id');
        $targetRole->permissions()->sync($permissionIds);

        return response()->json(['message' => 'Permissions updated successfully', 'role' => $targetRole->load('permissions')]);
    }
}
