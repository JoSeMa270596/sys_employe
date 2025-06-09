<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Empleados",
 *     description="Operaciones con empleados"
 * )
 */
class EmployeController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/employees",
     * summary="Obtener la lista de todos los empleados",
     * tags={"Empleados"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="name",
     * in="query",
     * required=false,
     * description="Filtrar por nombre de usuario",
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="department_id",
     * in="query",
     * required=false,
     * description="Filtrar por ID de departamento",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="status",
     * in="query",
     * required=false,
     * description="Filtrar por estado del empleado (activo/inactivo)",
     * @OA\Schema(type="string", enum={"activo", "inactivo"})
     * ),
     * @OA\Parameter(
     * name="hire_date",
     * in="query",
     * required=false,
     * description="Filtrar por fecha de contratación (YYYY-MM-DD)",
     * @OA\Schema(type="string", format="date")
     * ),
     * @OA\Response(
     * response=200,
     * description="Lista de empleados obtenida correctamente",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="current_page", type="integer", example=1),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Employee")),
     * @OA\Property(property="first_page_url", type="string", example="http://localhost:8000/api/employees?page=1"),
     * @OA\Property(property="from", type="integer", example=1),
     * @OA\Property(property="last_page", type="integer", example=1),
     * @OA\Property(property="last_page_url", type="string", example="http://localhost:8000/api/employees?page=1"),
     * @OA\Property(property="next_page_url", type="string", example=null),
     * @OA\Property(property="path", type="string", example="http://localhost:8000/api/employees"),
     * @OA\Property(property="per_page", type="integer", example=10),
     * @OA\Property(property="prev_page_url", type="string", example=null),
     * @OA\Property(property="to", type="integer", example=1),
     * @OA\Property(property="total", type="integer", example=1)
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
        if (!$request->user()->can('list_employe')) {
            abort(403, 'No tienes permiso para listar empleados');
        }
        $query = Employee::with(['user', 'department']);

        // Filtros dinámicos
        if ($request->filled('name')) {
            $query->whereHas('user', fn ($q) =>
                $q->where('name', 'like', '%' . $request->name . '%')
            );
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('hire_date')) {
            $query->whereDate('hire_date', $request->hire_date);
        }

        return response()->json($query->paginate(10));
    }

    /**
     * @OA\Post(
     * path="/api/employees",
     * summary="Crear un nuevo empleado",
     * tags={"Empleados"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"user_id","first_name","last_name","department_id","hire_date"},
     * @OA\Property(property="user_id", type="integer", example="1"),
     * @OA\Property(property="first_name", type="string", example="Jane"),
     * @OA\Property(property="last_name", type="string", example="Doe"),
     * @OA\Property(property="department_id", type="integer", example="1"),
     * @OA\Property(property="hire_date", type="string", format="date", example="2023-01-15"),
     * @OA\Property(property="status", type="string", enum={"activo", "inactivo"}, example="activo")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Empleado creado correctamente",
     * @OA\JsonContent(ref="#/components/schemas/Employee")
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
        if (!$request->user()->can('create_employe')) {
            abort(403, 'No tienes permiso para crear empleados');
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'hire_date' => 'required|date',
            'status' => 'in:activo,inactivo',
        ]);
        $employee = Employee::create($validated);
        return response()->json($employee, 201);
    }

    /**
     * @OA\Get(
     * path="/api/employees/{id}",
     * summary="Obtener un empleado por su ID",
     * tags={"Empleados"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del empleado",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Empleado encontrado",
     * @OA\JsonContent(ref="#/components/schemas/Employee")
     * ),
     * @OA\Response(
     * response=404,
     * description="Empleado no encontrado"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado"
     * )
     * )
     */
    public function show(string $id, Request $request)
    {
        if (!$request->user()->can('list_employe')) {
            abort(403, 'No tienes permiso para ver empleados');
        }
        $employee = Employee::with('user')->find($id);
        if (!$employee) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }
        return response()->json($employee);
    }

    /**
     * @OA\Put(
     * path="/api/employees/{id}",
     * summary="Actualizar un empleado existente",
     * tags={"Empleados"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del empleado a actualizar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=false,
     * @OA\JsonContent(
     * @OA\Property(property="first_name", type="string", example="Janet"),
     * @OA\Property(property="last_name", type="string", example="Smith"),
     * @OA\Property(property="department_id", type="integer", example="2"),
     * @OA\Property(property="hire_date", type="string", format="date", example="2023-02-20"),
     * @OA\Property(property="status", type="string", enum={"activo", "inactivo"}, example="inactivo")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Empleado actualizado correctamente",
     * @OA\JsonContent(ref="#/components/schemas/Employee")
     * ),
     * @OA\Response(
     * response=404,
     * description="Empleado no encontrado"
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
        if (!$request->user()->can('edit_employe')) {
            abort(403, 'No tienes permiso para editar empleados');
        }
        $employee = Employee::with('user')->find($id);
        $validated = $request->validate([
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'department_id' => 'sometimes|exists:departments,id',
            'hire_date' => 'sometimes|date',
            'status' => 'sometimes|in:activo,inactivo',
        ]);

        $employee->update($validated);
        return response()->json($employee);
    }

    /**
     * @OA\Delete(
     * path="/api/employees/{id}",
     * summary="Eliminar un empleado",
     * tags={"Empleados"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID del empleado a eliminar",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Empleado eliminado correctamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Empleado eliminado correctamente")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Empleado no encontrado"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autorizado"
     * )
     * )
     */
    public function destroy(string $id, Request $request)
    {
        if (!$request->user()->can('delete_employe')) {
            abort(403, 'No tienes permiso para eliminar empleados');
        }
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['error' => 'Empleado no encontrado'], 404);
        }
        $employee->delete();
        return response()->json(['message' => 'Empleado eliminado correctamente']);
    }
}
