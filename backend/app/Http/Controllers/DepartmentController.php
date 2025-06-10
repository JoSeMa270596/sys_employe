<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 * name="Departamentos",
 * description="Operaciones relacionadas con la gestión de departamentos"
 * )
 */
class DepartmentController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/departments",
     * summary="Obtener la lista de todos los departamentos",
     * tags={"Departamentos"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Lista de departamentos obtenida correctamente",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Department")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado"
     * )
     * )
     */
    public function index(Request $request)
    {
        if (!$request->user()->can('list_department')) {
            abort(403, 'No tienes permiso para listar departamentos');
        }
        return Department::with('chief')->get();
    }

    /**
     * @OA\Post(
     * path="/api/departments",
     * summary="Crear un nuevo departamento",
     * tags={"Departamentos"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name"},
     * @OA\Property(property="name", type="string", example="Recursos Humanos")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Departamento creado correctamente",
     * @OA\JsonContent(ref="#/components/schemas/Department")
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado"
     * )
     * )
     */
    public function store(Request $request)
    {
        if (!$request->user()->can('create_department')) {
            abort(403, 'No tienes permiso para crear departamentos');
        }
        $validated = $request->validate([
            'name' => 'required|string|unique:departments,name',
            'description' => 'nullable|string',
        ]);

        $department = Department::create($validated);
        return response()->json($department, 201);
    }

    /**
     * @OA\Get(
     * path="/api/departments/{id}",
     * summary="Obtener un departamento por su ID",
     * tags={"Departamentos"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del departamento",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Departamento encontrado",
     * @OA\JsonContent(ref="#/components/schemas/Department")
     * ),
     * @OA\Response(
     * response=404,
     * description="Departamento no encontrado"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado"
     * )
     * )
     */
    public function show(string $id, Request $request)
    {
        if (!$request->user()->can('list_user')) {
            abort(403, 'No tienes permiso para ver departamentos');
        }
        $department = Department::with('chief')->find($id);

        if (!$department) {
            return response()->json(['error' => 'Departamento no encontrado'], 404);
        }

        return response()->json($department);
    }

    /**
     * @OA\Put(
     * path="/api/departments/{id}",
     * summary="Actualizar un departamento existente",
     * tags={"Departamentos"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del departamento a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name"},
     * @OA\Property(property="name", type="string", example="Departamento de Ventas")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Departamento actualizado correctamente",
     * @OA\JsonContent(ref="#/components/schemas/Department")
     * ),
     * @OA\Response(
     * response=404,
     * description="Departamento no encontrado"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado"
     * )
     * )
     */
    public function update(Request $request, string $id)
    {
        if (!$request->user()->can('edit_department')) {
            abort(403, 'No tienes permiso para editar departamentos');
        }
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['error' => 'Departamento no encontrado'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
        ]);

        $department->update($validated);
        return response()->json($department);
    }

    /**
     * @OA\Delete(
     * path="/api/departments/{id}",
     * summary="Eliminar un departamento",
     * tags={"Departamentos"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del departamento a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Departamento eliminado correctamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Departamento eliminado correctamente")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Departamento no encontrado"
     * ),
     * @OA\Response(
     * response=400,
     * description="No se puede eliminar un departamento con empleados asociados"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado"
     * )
     * )
     */
    public function destroy(string $id, Request $request)
    {
        if (!$request->user()->can('delete_department')) {
            abort(403, 'No tienes permiso para eliminar departamentos');
        }
        $department = Department::find($id);

        if (!$department) {
            return response()->json(['error' => 'Departamento no encontrado'], 404);
        }

        // Verifica si hay empleados asociados
        if ($department->employees()->exists()) {
            return response()->json(['error' => 'No se puede eliminar un departamento con empleados asociados'], 400);
        }

        $department->delete();
        return response()->json(['message' => 'Departamento eliminado correctamente']);
    }

    /**
     * @OA\Patch(
     * path="/api/departments/{department}/assign-chief",
     * summary="Asignar un jefe a un departamento",
     * tags={"Departamentos"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="department",
     * in="path",
     * required=true,
     * description="ID del departamento",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"chief_employee_id"},
     * @OA\Property(property="chief_employee_id", type="integer", example="1")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Jefe asignado correctamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Jefe asignado correctamente")
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Error en la asignación del jefe (ej. el jefe no pertenece al departamento, ya es jefe de otro departamento)"
     * ),
     * @OA\Response(
     * response=404,
     * description="Departamento no encontrado"
     * ),
     * @OA\Response(
     * response=422,
     * description="Error de validación"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado"
     * )
     * )
     */
    public function assignChief(Request $request, Department $department)
    {
        if (!$request->user()->can('assign_chief')) {
            abort(403, 'No tienes permiso para asignar fejes a departamentos');
        }
        $validated = $request->validate([
            'chief_employee_id' => 'required|exists:employees,id',
        ]);

        $chief = Employee::find($validated['chief_employee_id']);

        // Validaciones clave
        if ($chief->department_id !== $department->id) {
            return response()->json(['error' => 'El jefe debe pertenecer al departamento.'], 400);
        }

        // Validar que no sea jefe de otro departamento
        $isChiefElsewhere = Department::where('chief_employee_id', $chief->id)
            ->where('id', '!=', $department->id)
            ->exists();

        if ($isChiefElsewhere) {
            return response()->json(['error' => 'Este empleado ya es jefe de otro departamento.'], 400);
        }

        $department->update(['chief_employee_id' => $chief->id]);

        return response()->json(['message' => 'Jefe asignado correctamente']);
    }
}
