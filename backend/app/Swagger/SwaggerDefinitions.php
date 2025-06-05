<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="API de Gestión de Empleados con Autenticación",
 *     version="1.0.0",
 *     description="Documentación completa de la API",
 *     @OA\Contact(
 *         email="jose.mendoza270596@gmail.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 */
class SwaggerDefinitions {}
