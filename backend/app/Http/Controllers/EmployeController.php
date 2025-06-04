<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API de Gestión de Empleados",
 *     version="1.0.0",
 *     description="Documentación de la API de empleados usando Swagger"
 * )
 */
class EmployeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/employees",
     *     summary="Listar empleados",
     *     tags={"Empleados"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de empleados"
     *     )
     * )
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
