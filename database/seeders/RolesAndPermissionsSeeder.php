<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = ['manage book', 'manage category'];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign created permissions
        $role1 = Role::create(['name' => 'admin']);
        $role1->givePermissionTo(Permission::all());

        $role2 = Role::create(['name' => 'manager']);
        $role2->givePermissionTo(['manage book']);

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $user->assignRole($role1);

        $user = \App\Models\User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
        ]);
        $user->assignRole($role2);
    }
}
