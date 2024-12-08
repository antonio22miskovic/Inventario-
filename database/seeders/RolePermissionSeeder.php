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
        $userRole = Role::create(['name' => 'user']);

        // Crear permisos
        $permissions = [
            'manage products',
            'view dashboard',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar permisos al rol admin
        $adminRole->givePermissionTo($permissions);

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
