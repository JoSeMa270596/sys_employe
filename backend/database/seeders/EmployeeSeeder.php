<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(Department $department, bool $isChief = false): void
    {
        // Crear usuario primero
        $user = User::create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), // Todos con contraseña 'password'
        ]);
        // Crear empleado asociado al usuario
        $employee = Employee::create([
            'user_id' => $user->id,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'status' => 'active',
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now'),
            'department_id' => $department->id,
        ]);
        // Si es jefe, actualizar el departamento
        if ($isChief) {
            $department->update(['chief_employee_id' => $employee->id]);

            // Asignar rol de jefe al usuario
            $user->assignRole('chief');
        } else {
            // Asignar rol de empleado regular
            $user->assignRole('employee');
        }
    }
}
