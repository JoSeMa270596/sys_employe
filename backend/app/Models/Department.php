<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(
 * schema="Department",
 * title="Department",
 * description="Modelo de Departamento",
 * @OA\Property(property="id", type="integer", readOnly="true", example=1),
 * @OA\Property(property="name", type="string", example="Recursos Humanos"),
 * @OA\Property(property="chief_employee_id", type="integer", nullable=true, example=10),
 * @OA\Property(property="created_at", type="string", format="date-time", readOnly="true", example="2023-10-27T10:00:00.000000Z"),
 * @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true", example="2023-10-27T10:00:00.000000Z"),
 * @OA\Property(property="chief", ref="#/components/schemas/Employee", nullable=true)
 * )
 */
class Department extends Model
{
    protected $fillable = ['name', 'description'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function chief()
    {
        return $this->belongsTo(Employee::class, 'chief_employee_id');
    }
}
