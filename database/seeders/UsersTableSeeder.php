<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        $financeRole = Role::create(['name' => 'finance']);
        $subscriptionRole = Role::create(['name' => 'subscription']);
        $contentCreatorRole = Role::create(['name' => 'content_creator']);

        // Create permissions
        $manageUsersPermission = Permission::create(['name' => 'manage_users']);
        $manageContentPermission = Permission::create(['name' => 'manage_content']);

        // Assign permissions to roles
        $adminRole->givePermissionTo($manageUsersPermission, $manageContentPermission);
        $financeRole->givePermissionTo($manageUsersPermission);
        $subscriptionRole->givePermissionTo($manageUsersPermission);
        $contentCreatorRole->givePermissionTo($manageContentPermission);

        // Create users with roles
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password')
        ])->assignRole('admin');

        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password')
        ])->assignRole('user');

        User::create([
            'name' => 'Finance',
            'email' => 'finance@example.com',
            'password' => Hash::make('password')
        ])->assignRole('finance');

        User::create([
            'name' => 'Subscription',
            'email' => 'subscription@example.com',
            'password' => Hash::make('password')
        ])->assignRole('subscription');

        User::create([
            'name' => 'Content Creator',
            'email' => 'content_creator@example.com',
            'password' => Hash::make('password')
        ])->assignRole('content_creator');

        // Add more users with roles as needed
    }
}


