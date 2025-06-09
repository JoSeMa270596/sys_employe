<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        $permissions = [
            'view_dashboard',
            // user
            'list_user',
            'view_user',
            'create_user',
            'edit_user',
            'delete_user',
            // employe
            'list_employe',
            'create_employe',
            'edit_employe',
            'delete_employe',
            // department
            'list_department',
            'create_department',
            'edit_department',
            'delete_department',
            'assign_chief',
        ];
        $apiGuard = 'api';
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => $apiGuard]);
        }
        // Rol Admin - Todos los permisos
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $apiGuard]);
        $admin->givePermissionTo(Permission::where('guard_name', $apiGuard)->get());

        // Rol Jefe
        $jefe = Role::firstOrCreate(['name' => 'chief', 'guard_name' => $apiGuard]);
        $jefe->givePermissionTo([
            'view_dashboard',
            'view_user',
            'list_employe'
        ]);

        // Rol Empleado
        $empleado = Role::firstOrCreate(['name' => 'employee', 'guard_name' => $apiGuard]);
        $empleado->givePermissionTo([
            'view_dashboard',
            'view_user'
        ]);
    }
}
