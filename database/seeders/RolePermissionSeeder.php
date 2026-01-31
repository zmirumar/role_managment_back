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
            'ADMIN' => [
                'name' => 'Admin',
                'permissions' => array_keys($permissions), // Admin gets all permissions
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

        // Create Admin User
        $adminRole = Role::where('slug', 'ADMIN')->first();
        User::updateOrCreate(
            ['username' => 'mirumar'], // Change this to your desired admin username
            [
                'password' => Hash::make('boshliqn1'), // Change this to your desired admin password
                'role_id' => $adminRole->id,
            ]
        );
    }
}
