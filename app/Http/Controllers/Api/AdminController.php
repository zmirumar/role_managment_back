<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::with('role:id,name,slug')->get();
        return response()->json($users);
    }

    public function changeRole(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|exists:roles,slug',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $role = Role::where('slug', $request->role)->first();
        $user->role()->associate($role);
        $user->save();

        return response()->json(['message' => 'User role updated successfully', 'user' => $user->load('role')]);
    }

    public function roles()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function updatePermissions(Request $request, $role)
    {
        $targetRole = Role::where('slug', $role)->first();

        if (!$targetRole) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,slug',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $permissionIds = Permission::whereIn('slug', $request->permissions ?? [])->pluck('id');
        $targetRole->permissions()->sync($permissionIds);

        return response()->json(['message' => 'Permissions updated successfully', 'role' => $targetRole->load('permissions')]);
    }
}
