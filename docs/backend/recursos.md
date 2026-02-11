# Módulo de Recursos (Categorías)

El módulo de Recursos gestiona las **categorías de equipos** que pueden existir en el inventario. No se debe confundir con los ítems individuales de inventario.

## 1. Descripción General

**Controlador:** `RecursosController`
**Modelos:** `Recurso` (Mapea a la tabla `categorias_equipo`)

Este módulo es responsable de:
- Definir qué tipos de equipos pueden ser inventariados.
- Establecer si una categoría requiere mantenimiento mensual.
- Habilitar o deshabilitar categorías completas.

## 2. Endpoints

Todas las acciones requieren rol de **Administrador (ID: 2)**.

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `recursos` | GET | Lista las categorías y conteo de ítems asociados. | Admin |
| `recursos/create` | GET | Muestra formulario de creación. | Admin |
| `recursos/store` | POST | Guarda una nueva categoría. | Admin |
| `recursos/edit/{id}` | GET | Muestra formulario de edición. | Admin |
| `recursos/update/{id}` | POST | Actualiza la categoría. | Admin |
| `recursos/delete/{id}` | GET | Desactiva (soft delete) una categoría. | Admin |
| `recursos/toggleStatus/{id}` | GET | Alterna el estado activo/inactivo. | Admin |

## 3. Lógica de Negocio

### Categorías Permitidas
El sistema restringe los nombres de categorías a una lista predefinida en el controlador para mantener consistencia:
*   Computadora, Laptop, Proyector, Impresora, Monitor, Servidor, Equipo de Red, Periférico, Accesorio, Cámara, Equipo de Sonido.

### Relación con Inventario
El listado principal muestra cuántos ítems de inventario existen para cada categoría mediante una subconsulta SQL.

## 4. Modelos Relacionados

*   **Recurso (`app/Models/Recurso.php`):**
    *   `getAll()`: Obtiene categorías y conteo de inventario.
    *   `create($data)`, `update($id, $data)`.
    *   `delete($id)`, `toggleStatus($id)`: Gestión de estado `esta_activo`.

## 5. Validaciones

*   **Nombre Categoría:** Debe estar en la lista de `alloweCategories` y ser único en la base de datos.
*   **Observación:** Máximo 500 caracteres.

## 6. Seguridad

*   **RBAC:** Uso estricto de `$this->requireRole([2])` en todos los métodos.
