<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            'post.read' => 'Read Posts',
            'post.create' => 'Create Posts',
            'post.edit' => 'Edit Posts',
            'post.delete' => 'Delete Posts',
        ];

        $permissionModels = [];
        foreach ($permissions as $slug => $name) {
            $permissionModels[$slug] = Permission::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }

        // Define Roles
        $roles = [
            'OWNER' => [
                'name' => 'Owner',
                'permissions' => array_keys($permissions),
            ],
            'SUPERADMIN' => [
                'name' => 'Super Admin',
                'permissions' => ['post.read', 'post.create', 'post.edit', 'post.delete'],
            ],
            'ADMIN' => [
                'name' => 'Admin',
                'permissions' => ['post.read', 'post.create'],
            ],
            'USER' => [
                'name' => 'User',
                'permissions' => ['post.read'],
            ],
        ];

        foreach ($roles as $slug => $roleData) {
            $role = Role::updateOrCreate(
                ['slug' => $slug],
                ['name' => $roleData['name']]
            );

            $rolePermissions = [];
            foreach ($roleData['permissions'] as $pSlug) {
                $rolePermissions[] = $permissionModels[$pSlug]->id;
            }
            $role->permissions()->sync($rolePermissions);
        }

        // Create Owner User
        $ownerRole = Role::where('slug', 'OWNER')->first();
        User::updateOrCreate(
            ['username' => 'owner'],
            [
                'password' => Hash::make('password'),
                'role_id' => $ownerRole->id,
            ]
        );
    }
}
