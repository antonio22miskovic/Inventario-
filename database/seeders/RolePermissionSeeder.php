<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole1  = Role::create(['name' => 'user']);
        $userRole2  = Role::create(['name' => 'user_stats']);
        $userRole3  = Role::create(['name' => 'user_movements']);

        // Crear permisos
        $permissions = [
            'admin',
            'manage users',
            'manage products',
            'manage categories',
            'view stats',
            'manage movements'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar permisos al rol admin
        $adminRole->givePermissionTo($permissions);
        $userRole1->givePermissionTo(['manage products']);
        $userRole2->givePermissionTo(['view stats']);
        $userRole3->givePermissionTo(['manage movements']);

        $user = User::firstOrCreate([
            'email' => 'admin@test.com',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('admin1234'), // Cambia esto por una contraseña segura
        ]);

        // Asignar un rol a un usuario
        if ($user) {
            $user->assignRole('admin');
        }
    }
}
