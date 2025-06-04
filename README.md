# Sistema de Gestión de Empleados

Este proyecto es una solución para la gestión de empleados de una empresa, desarrollado con **Laravel (backend)** y **Angular (frontend)**, utilizando **Docker** como entorno de desarrollo.

## Características

- API RESTful desarrollada en Laravel 12
- Autenticación de usuarios mediante JWT
- Gestión de empleados (crear, listar, editar, eliminar)
- Filtros de búsqueda por nombre, departamento, estado, fecha de ingreso
- Gestión de departamentos y asignación de jefes
- Reporte jerárquico por departamento
- Docker para contenerización de servicios
- Documentación con Swagger
- Frontend Angular como plus

---

## Tecnologías

- PHP 8.2 + Laravel
- MySQL
- Angular 17
- Docker y Docker Compose
- JWT para autenticación

---

## Instalación

### Requisitos

- Docker y Docker Compose instalados
- Git instalado

### Clonar el repositorio

```bash
git clone https://github.com/tuusuario/empleados-system.git
cd empleados-system
```

### Contruir el contenedor
```bash
docker-compose up --build
```

### Ejecutar la migracion
```bash
docker exec -it laravel_app php artisan migrate
```
