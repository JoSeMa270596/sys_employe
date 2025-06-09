<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hola este es un sistema de gestion de emppleados!']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rutas para Departamentos
    // Protegidas con 'jwt.auth' y permisos específicos de Spatie
    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index']);//->middleware('permission:list_department');
        Route::post('/', [DepartmentController::class, 'store']);//->middleware('permission:create_department');
        Route::get('/{id}', [DepartmentController::class, 'show']);//->middleware('permission:list_department');
        Route::put('/{id}', [DepartmentController::class, 'update']);//->middleware('permission:edit_department');
        Route::delete('/{id}', [DepartmentController::class, 'destroy']);//->middleware('permission:delete_department');
        Route::patch('/{department}/assign-chief', [DepartmentController::class, 'assignChief']);//->middleware('permission:assign_chief');
    });

    // Rutas para Empleados
    // Protegidas con 'jwt.auth' y permisos específicos de Spatie
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeController::class, 'index']);//->middleware('permission:list_employe');
        Route::post('/', [EmployeController::class, 'store']);//->middleware('permission:create_employe');
        Route::get('/{id}', [EmployeController::class, 'show']);//->middleware('permission:list_employe');
        Route::put('/{id}', [EmployeController::class, 'update']);//->middleware('permission:edit_employe');
        Route::delete('/{id}', [EmployeController::class, 'destroy']);//->middleware('permission:delete_employe');
    });
});
