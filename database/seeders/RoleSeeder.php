<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = User::findOrCreate('test@example.com', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $permissions = [];
        $permissions_title = [];

        $role = Role::findOrCreate('admin');
        $role->updateQuietly(['title' => 'مدیر']);
        if (! $admin->hasAnyRole($role)) {
            $admin->assignRole($role);
        }

        foreach ($permissions as $key => $item) {
            if (! Permission::findByName($item) instanceof Permission) {
                Permission::create(['name' => $item, 'title' => $permissions_title[$key]]);
                $role->givePermissionTo($item);
            }
        }

        $user_permissions = [];
        $user_permissions_title = [];

        $user_role = Role::findOrCreate('user');
        $user_role->updateQuietly(['title' => 'کاربر']);

        foreach ($user_permissions as $key => $item) {
            if (! Permission::findByName($item) instanceof Permission) {
                Permission::create(['name' => $item, 'title' => $user_permissions_title[$key]]);
                $user_role->givePermissionTo($item);
            }
        }
    }
}
