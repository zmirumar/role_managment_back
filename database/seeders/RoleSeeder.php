<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $createPost = \App\Models\Permission::firstOrCreate(['slug' => 'create_post'], ['name' => 'Create Post']);
        $editPost = \App\Models\Permission::firstOrCreate(['slug' => 'edit_post'], ['name' => 'Edit Post']);
        $deletePost = \App\Models\Permission::firstOrCreate(['slug' => 'delete_post'], ['name' => 'Delete Post']);

        // Create Roles
        $userRole = \App\Models\Role::firstOrCreate(['slug' => 'USER'], ['name' => 'User']);
        $adminRole = \App\Models\Role::firstOrCreate(['slug' => 'ADMIN'], ['name' => 'Admin']);
        $superAdminRole = \App\Models\Role::firstOrCreate(['slug' => 'SUPERADMIN'], ['name' => 'Super Admin']);
        $ownerRole = \App\Models\Role::firstOrCreate(['slug' => 'OWNER'], ['name' => 'Owner']);

        // Assign Permissions to Roles
        // USER: No special permissions (Read only)

        // ADMIN: Can create posts
        $adminRole->permissions()->syncWithoutDetaching([$createPost->id]);

        // SUPERADMIN: Can create, edit, delete posts
        $superAdminRole->permissions()->syncWithoutDetaching([$createPost->id, $editPost->id, $deletePost->id]);

        // OWNER: Full access (also given blog permissions for consistency)
        $ownerRole->permissions()->syncWithoutDetaching([$createPost->id, $editPost->id, $deletePost->id]);
    }
}
