<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $initial_roles = config('myconstants.initial_roles');
        $initial_permissions = config('myconstants.initial_permissions');

        foreach ($initial_roles as $role) {
            $new_role = Role::create(['name' => $role]);
            $new_role->permissions()->detach();
            foreach ($initial_permissions[$role] as $permission) {
                $new_permission = Permission::updateOrCreate(['name' => $permission]);
                $new_role->permissions()->attach($new_permission->id);

            }
        }

    }
}
