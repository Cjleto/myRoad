<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a user foreach roles
        $roles = Role::all();

        foreach ($roles as $role) {
            User::create([
                'name' => Str::title($role->name) . ' User',
                'email' => Str::lower($role->name) . '@example.com',
                'password' => bcrypt('password'),
                'roleId' => $role->id,
            ]);
        }
    }
}
