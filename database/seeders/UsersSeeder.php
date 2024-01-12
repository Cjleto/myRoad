<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', config('myconstants.role.admin'))->first();
        $editorRole = Role::where('name', config('myconstants.role.editor'))->first();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'roleId' => $adminRole->id,
        ]);

        User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => bcrypt('password'),
            'roleId' => $editorRole->id,
        ]);

    }
}
