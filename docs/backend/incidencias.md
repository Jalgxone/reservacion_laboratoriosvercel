# Módulo de Incidencias

El módulo de Incidencias permite a los usuarios reportar problemas con los equipos y a los administradores gestionar su resolución.

## 1. Descripción General

**Controlador:** `IncidenciasController`
**Modelos:** `Incidencia`, `Inventario`, `Usuario`

Este módulo es responsable de:
- Registrar reportes de fallos en equipos específicos.
- Clasificar los problemas por nivel de gravedad.
- Dar seguimiento al estado de resolución del problema.

## 2. Endpoints

El acceso varía según el rol: cualquier usuario puede reportar, pero solo administradores pueden gestionar.

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `incidencias` | GET | Lista todas las incidencias reportadas. | Admin |
| `incidencias/create` | GET | Muestra el formulario de reporte. | Autenticado |
| `incidencias/store` | POST | Guarda el reporte de incidencia. | Autenticado |
| `incidencias/edit/{id}` | GET | Muestra formulario de edición (p.ej. para resolver). | Admin |
| `incidencias/update/{id}` | POST | Actualiza la incidencia (estado, gravedad). | Admin |
| `incidencias/delete/{id}` | GET | Elimina el registro de una incidencia. | Admin |

## 3. Lógica de Negocio

### Roles
-   **Usuarios:** Pueden crear reportes indicando el equipo y la descripción del fallo.
-   **Administradores:** Pueden ver todos los reportes, editar su gravedad y marcar si han sido resueltos.

### Atributos Clave
-   **Gravedad:** `baja`, `media`, `alta`.
-   **Estado:** `resuelto` (booleano).
-   **Relación:** Cada incidencia está obligatoriamente vinculada a un ítem del inventario (`id_equipo`) y un usuario reportante.

## 4. Modelos Relacionados

*   **Incidencia (`app/Models/Incidencia.php`):**
    *   Métodos CRUD básicos.
    *   `getAll()`: Realiza JOINs para traer el serial del equipo y el nombre del usuario reportante.
*   **Inventario:** Se utiliza para listar los equipos disponibles al crear el reporte.

## 5. Validaciones

*   **Equipo:** Debe ser un ID válido existente en la tabla `inventario`.
*   **Descripción:** Requerida, longitud mínima 10 y máxima 1000 caracteres.
*   **Gravedad:** Debe ser uno de los valores permitidos (`baja`, `media`, `alta`).

## 6. Seguridad

*   `create/store` accesibles para todos los usuarios logueados (`requireRole([])`).
*   Gestión restringida a administradores (`requireRole([2])`).
