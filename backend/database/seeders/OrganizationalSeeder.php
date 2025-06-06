<?php

namespace Database\Seeders;

use App\Models\Department;
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

        // 1. Crear Administrador
        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@empresa.com',
            'password' => Hash::make('Admin123'),
        ]);
        $admin->assignRole($adminRole);

        // 2. Crear Departamentos
        $departments = [
            [
                'name' => 'Ventas',
                'description' => 'Departamento de ventas y atención al cliente'
            ],
            [
                'name' => 'Desarrollo',
                'description' => 'Departamento de desarrollo de software'
            ],
            [
                'name' => 'Recursos Humanos',
                'description' => 'Departamento de gestión de personal'
            ]
        ];

        foreach ($departments as $departmentData) {
            $department = Department::create($departmentData);

            // Llamar al seeder de empleados para este departamento
            $this->callWith(EmployeeSeeder::class, [
                'department' => $department,
                'isChief' => true
            ]);

            // Crear 4 empleados adicionales (no jefes)
            for ($i = 0; $i < 4; $i++) {
                $this->callWith(EmployeeSeeder::class, [
                    'department' => $department,
                    'isChief' => false
                ]);
            }
        }
    }
}
