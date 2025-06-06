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
            'edith_user',
            'delete_user',
            // employe
            'list_employe',
            'create_employe',
            'edith_employe',
            'delete_employe',
            // boss
            'list_boss',
            'create_boss',
            'edith_boss',
            'delete_boss',
            'view_reports',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        // Rol Admin - Todos los permisos
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Rol Jefe
        $jefe = Role::firstOrCreate(['name' => 'chief']);
        $jefe->givePermissionTo([
            'view_dashboard',
            'view_user',
            'list_employe',
            'view_reports'
        ]);

        // Rol Empleado
        $empleado = Role::firstOrCreate(['name' => 'employee']);
        $empleado->givePermissionTo([
            'view_dashboard',
            'view_user'
        ]);
    }
}
