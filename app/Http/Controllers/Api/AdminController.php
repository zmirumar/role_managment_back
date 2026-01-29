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
        $users = User::with('role:id,name,slug')->get();
        return response()->json($users);
    }

    public function changeRole(UpdateUserRoleRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if user is OWNER (Owner cannot be modified)
        if ($user->hasRole('OWNER')) {
            return response()->json(['message' => 'Owner role cannot be modified'], 403);
        }

        $role = Role::where('slug', $request->role)->first();
        
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        // Cannot change user to OWNER
        if ($role->slug === 'OWNER') {
            return response()->json(['message' => 'Cannot assign OWNER role'], 403);
        }

        $user->role()->associate($role);
        $user->save();

        return response()->json(['message' => 'User role updated successfully', 'user' => $user->load('role')]);
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

        // Check if role is OWNER (Owner's permissions skip checks and technically don't need update)
        if ($targetRole->slug === 'OWNER') {
            return response()->json(['message' => 'Owner permissions cannot be modified'], 403);
        }

        $permissionIds = Permission::whereIn('slug', $request->permissions ?? [])->pluck('id');
        $targetRole->permissions()->sync($permissionIds);

        return response()->json(['message' => 'Permissions updated successfully', 'role' => $targetRole->load('permissions')]);
    }
}
