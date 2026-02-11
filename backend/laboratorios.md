# Módulo de Laboratorios

El módulo de Laboratorios permite la gestión de los espacios físicos disponibles para reservación.

## 1. Descripción General

**Controlador:** `LaboratoriosController`
**Modelos:** `Laboratorio`

Este módulo es responsable de:
- Listar los laboratorios disponibles.
- Registrar nuevos laboratorios con su capacidad y ubicación.
- Modificar la información de laboratorios existentes.
- Habilitar o deshabilitar laboratorios (toggle de estado).
- Eliminar laboratorios (con validaciones de integridad).

## 2. Endpoints

El listado (`index`) es accesible para cualquier usuario autenticado, pero las acciones de gestión (crear, editar, eliminar) requieren rol de **Administrador (ID: 2)**.

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `laboratorios` | GET | Lista todos los laboratorios. | Autenticado |
| `laboratorios/create` | GET | Muestra el formulario de creación. | Admin |
| `laboratorios/store` | POST | Procesa la creación del laboratorio. | Admin |
| `laboratorios/edit/{id}` | GET | Muestra el formulario de edición. | Admin |
| `laboratorios/update/{id}` | POST | Actualiza los datos del laboratorio. | Admin |
| `laboratorios/delete/{id}` | GET | Elimina (o desactiva) un laboratorio. Soporta AJAX. | Admin |
| `laboratorios/toggleStatus/{id}` | GET | Alterna el estado activo/inactivo. | Admin |

## 3. Lógica de Negocio

### Gestión de Estado
-   **Toggle:** Permite habilitar/deshabilitar rápidamente un laboratorio sin eliminarlo.
-   **Borrado:** La eliminación intenta marcar como inactivo (`esta_activo = 0`).

### Restricciones
-   **Capacidad:** Se valida que la capacidad esté entre 10 y 50 personas.
-   **Eliminación:** Si se intenta eliminar via AJAX, el sistema captura excepciones de integridad referencial (ej: si tiene reservas asociadas) y retorna un error 400.

## 4. Modelos Relacionados

*   **Laboratorio (`app/Models/Laboratorio.php`):**
    *   `getAll()`: Devuelve todos los registros.
    *   `create($data)`: Inserta nuevo laboratorio.
    *   `update($id, $data)`: Actualiza registro.
    *   `delete($id)`: Realiza un *Soft Delete* (update `esta_activo = 0`).
    *   `toggleStatus($id)`: Invierte el valor de `esta_activo`.

## 5. Validaciones

Se aplican las siguientes reglas mediante `Validator.php`:

*   **Nombre:** Requerido, alfanumérico con caracteres especiales permitidos, longitud 3-255.
*   **Ubicación:** Requerido, alfanumérico con caracteres especiales permitidos, max 255.
*   **Capacidad:** Requerido, entero entre 10 y 50.

## 6. Seguridad

*   **Control de Acceso (RBAC):** Las funciones administrativas invocan `$this->requireRole([2])`.
*   **Manejo de Errores:** Captura de excepciones `PDOException` (código 23000) para evitar violaciones de clave foránea al eliminar, informando al usuario.
