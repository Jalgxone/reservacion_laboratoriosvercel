# Módulo de Usuarios

El módulo de Usuarios permite la gestión integral de las cuentas de usuario del sistema, incluyendo administradores y clientes.

## 1. Descripción General

**Controlador:** `UsuariosController`
**Modelos:** `Usuario`, `Rol`

Este módulo es responsable de:
- Listar todos los usuarios registrados.
- Crear nuevos usuarios con roles específicos (Administrador o Cliente).
- Editar información de usuarios existentes.
- Aprobar usuarios recién registrados (estado 'pendiente').
- Activar o desactivar usuarios (borrado lógico).
- Gestionar roles y permisos.

## 2. Endpoints

Todos los endpoints de este módulo requieren que el usuario autenticado tenga el rol de **Administrador (ID: 2)**.

| Ruta | Método | Descripción | Parámetros |
| :--- | :--- | :--- | :--- |
| `usuarios` | GET | Lista todos los usuarios. | - |
| `usuarios/create` | GET | Muestra el formulario para crear un nuevo usuario. | - |
| `usuarios/store` | POST | Procesa la creación del usuario. | `nombre`, `apellido`, `cedula`, `telefono`, `email`, `password`, `id_rol` |
| `usuarios/edit/{id}` | GET | Muestra el formulario de edición de un usuario. | `id` (URL) |
| `usuarios/update/{id}` | POST | Actualiza los datos de un usuario. | `id` (URL), datos del formulario |
| `usuarios/approve/{id}` | GET | Cambia el estado de un usuario de 'pendiente' a 'activo'. | `id` (URL) |
| `usuarios/toggleStatus/{id}` | GET | Alterna el estado entre 'activo' e 'inactivo'. | `id` (URL) |
| `usuarios/delete/{id}` | GET | Desactiva un usuario (alias para lógica de borrado). | `id` (URL) |

## 3. Lógica de Negocio

### Gestión de Estado
-   **Aprobación:** Los usuarios registrados públicamente inician como 'pendientes'. El administrador debe aprobarlos manualmente.
-   **Borrado Lógico:** La acción `delete` no elimina el registro de la base de datos, sino que cambia su estado a 'inactivo'.
-   **Toggle:** Permite reactivar usuarios inactivos o desactivar activos rápidamente.

### Protección de Superusuario
-   Un administrador no puede desactivar (`delete`) ni cambiar el estado (`toggleStatus`, `update`) de su propia cuenta para evitar bloqueos accidentales (`$_SESSION['user_id'] == $id`).
-   Un administrador no puede quitarse sus propios privilegios de administrador.

## 4. Modelos Relacionados

*   **Usuario (`app/Models/Usuario.php`):**
    *   Métodos CRUD estándar: `getAll`, `create`, `update`, `delete`, `getById`.
*   **Rol (`app/Models/Rol.php`):**
    *   `getAll()`: Se utiliza para poblar el selector de roles en los formularios de creación y edición.

## 5. Validaciones

Se aplican las siguientes reglas mediante `Validator.php`:

*   **Nombre/Apellido:** Requeridos, longitud mínima/máxima.
*   **Cédula:** Formato venezolano (`ve_ci`), única en la tabla `usuarios`.
*   **Teléfono:** Formato venezolano (`ve_phone`).
*   **Email:** Formato válido, único en la tabla `usuarios`.
*   **Contraseña:**
    -   Creación: Requerida, min 8 caracteres.
    -   Edición: Opcional (si se deja vacía no se actualiza).

## 6. Seguridad

*   **Control de Acceso (RBAC):** Todos los métodos invocan `$this->requireRole([2])` al inicio, asegurando que solo los administradores puedan acceder.
*   **Prevención de Auto-Bloqueo:** Validaciones específicas para impedir que el usuario actual se degrade o desactive a sí mismo.
