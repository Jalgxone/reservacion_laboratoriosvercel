# Módulo de Inventarios

El módulo de Inventarios gestiona el registro físico de los equipos ubicados en los laboratorios.

## 1. Descripción General

**Controlador:** `InventariosController`
**Modelos:** `Inventario`, `Laboratorio`, `Recurso` (Categoría)

Este módulo es responsable de:
- Registrar equipos individuales asignándoles un código serial único.
- Asignar equipos a laboratorios específicos.
- Gestionar el estado operativo (Operativo/Baja) y el estado activo.
- Validar movimientos de inventario contra las reservas activas.

## 2. Endpoints

Todas las acciones requieren rol de **Administrador (ID: 2)**.

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `inventarios` | GET | Lista el inventario completo. | Admin |
| `inventarios/create` | GET | Muestra formulario de registro. | Admin |
| `inventarios/store` | POST | Guarda un nuevo equipo y genera su serial. | Admin |
| `inventarios/edit/{id}` | GET | Muestra formulario de edición. | Admin |
| `inventarios/update/{id}` | POST | Actualiza datos del equipo. | Admin |
| `inventarios/delete/{id}` | GET | Desactiva un equipo (baja lógica). | Admin |
| `inventarios/toggleStatus/{id}` | GET | Alterna el estado activo/inactivo. | Admin |

## 3. Lógica de Negocio

### Generación de Seriales
El sistema genera automáticamente un **Código Serial** con el formato `EQU-{id_categoria}-{secuencial}` (ej: `EQU-01-0045`) para identificar unívocamente cada ítem.

### Validaciones de Integridad (Business Logic)
El modelo `Inventario` impone restricciones estrictas para evitar inconsistencias con las reservas:

1.  **Movimiento de Equipos:** No se puede cambiar un equipo de laboratorio si el laboratorio de origen tiene reservas activas futuras.
2.  **Dar de Baja:** No se puede marcar un equipo como 'Baja' o 'Inactivo' si el laboratorio donde está asignado tiene reservas activas.
3.  **Eliminación:** No se puede eliminar si tiene incidencias pendientes de resolución.
4.  **Activación:** No se puede reactivar un equipo si tiene incidencias pendientes.

## 4. Modelos Relacionados

*   **Inventario (`app/Models/Inventario.php`):**
    *   `generateNextID($cat)`: Lógica de seriales.
    *   `hasActiveReservations($id)`: Verifica conflictos con reservas.
    *   `hasPendingIncidents($id)`: Verifica incidencias no resueltas.
    *   `create`, `update`, `delete`, `toggleStatus`: Métodos CRUD con validaciones integradas.

## 5. Validaciones

*   **Relaciones:** Laboratorio y Categoría deben existir.
*   **Marca/Modelo:** Formato de marca válido (Proper Case).
*   **Estado Operativo:** 'Operativo' o 'Baja'.

## 6. Seguridad

*   Acceso exclusivo para administradores (`requireRole([2])`).
*   Protección contra inconsistencia de datos mediante validaciones previas a la actualización en DB.
