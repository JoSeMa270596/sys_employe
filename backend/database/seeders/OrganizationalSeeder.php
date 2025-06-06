<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class OrganizationalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener roles
        $adminRole = Role::where('name', 'admin')->first();
        $jefeRole = Role::where('name', 'jefe')->first();
        $empleadoRole = Role::where('name', 'empleado')->first();

        // 1. Crear Administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@empresa.com',
            'password' => Hash::make('Admin123'),
        ]);
        $admin->assignRole($adminRole);

        // 2. Crear Jefes de Departamento
        $jefes = [
            [
                'name' => 'Jefe de Ventas',
                'email' => 'jefe.ventas@empresa.com',
                'password' => Hash::make('JefeVentas123'),
                'department' => 'Ventas'
            ],
            [
                'name' => 'Jefe de Producción',
                'email' => 'jefe.produccion@empresa.com',
                'password' => Hash::make('JefeProd123'),
                'department' => 'Producción'
            ]
        ];
        $createdJefes = [];
        foreach ($jefes as $jefeData) {
            $jefe = User::create($jefeData);
            $jefe->assignRole($jefeRole);
            $createdJefes[] = $jefe;
        }

        // 3. Crear Empleados (2 por cada jefe)
        $empleados = [
            // Empleados del Jefe de Ventas
            [
                'name' => 'Vendedor 1',
                'email' => 'vendedor1@empresa.com',
                'password' => Hash::make('Vendedor1123'),
                'department' => 'Ventas',
                'supervisor_id' => $createdJefes[0]->id
            ],
            [
                'name' => 'Vendedor 2',
                'email' => 'vendedor2@empresa.com',
                'password' => Hash::make('Vendedor2123'),
                'department' => 'Ventas',
                'supervisor_id' => $createdJefes[0]->id
            ],
            // Empleados del Jefe de Producción
            [
                'name' => 'Operador 1',
                'email' => 'operador1@empresa.com',
                'password' => Hash::make('Operador1123'),
                'department' => 'Producción',
                'supervisor_id' => $createdJefes[1]->id
            ],
            [
                'name' => 'Operador 2',
                'email' => 'operador2@empresa.com',
                'password' => Hash::make('Operador2123'),
                'department' => 'Producción',
                'supervisor_id' => $createdJefes[1]->id
            ]
        ];

        foreach ($empleados as $empleadoData) {
            $empleado = User::create($empleadoData);
            $empleado->assignRole($empleadoRole);
        }
    }
}
