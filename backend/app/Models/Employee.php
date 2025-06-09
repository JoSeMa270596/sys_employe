<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * schema="Employee",
 * title="Employee",
 * description="Modelo de Empleado",
 * @OA\Property(property="id", type="integer", readOnly="true", example=1),
 * @OA\Property(property="user_id", type="integer", example=1),
 * @OA\Property(property="first_name", type="string", example="Jane"),
 * @OA\Property(property="last_name", type="string", example="Doe"),
 * @OA\Property(property="department_id", type="integer", example=1),
 * @OA\Property(property="hire_date", type="string", format="date", example="2023-01-15"),
 * @OA\Property(property="status", type="string", enum={"activo", "inactivo"}, example="activo"),
 * @OA\Property(property="created_at", type="string", format="date-time", readOnly="true", example="2023-10-27T10:00:00.000000Z"),
 * @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true", example="2023-10-27T10:00:00.000000Z"),
 * @OA\Property(property="user", ref="#/components/schemas/User"),
 * @OA\Property(property="department", ref="#/components/schemas/Department")
 * )
 */
class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'status',
        'hire_date',
        'department_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
