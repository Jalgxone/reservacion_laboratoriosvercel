# Módulo de Autenticación

El módulo de Autenticación gestiona el acceso de los usuarios al sistema, el registro de nuevas cuentas, la recuperación de contraseñas y la gestión del perfil de usuario.

## 1. Descripción General

**Controlador:** `AuthController`
**Modelos:** `Usuario`, `Rol`

Este módulo es responsable de:
- Verificar las credenciales de los usuarios (email y contraseña).
- Gestionar sesiones de usuario y cookies de "Recuérdame".
- Permitir el registro de nuevos usuarios.
- Gestionar la recuperación de contraseña mediante tokens por email.
- Permitir a los usuarios ver y actualizar su perfil y contraseña.
- Proteger rutas que requieren autenticación.

## 2. Endpoints

### Login y Gestión de Sesión

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `auth` | GET | Muestra el formulario de login. Si ya hay sesión, redirige al dashboard. Verifica cookie `remember_me`. | Público |
| `auth/login` | POST | Procesa el inicio de sesión. | Público |
| `auth/logout` | GET/POST | Cierra la sesión y elimina cookies de autenticación. | Autenticado |
| `auth/dashboard` | GET | Muestra el panel principal del usuario autenticado. | Autenticado |

### Registro

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `auth/register` | GET | Muestra el formulario de registro. | Público |
| `auth/store` | POST | Procesa el registro de un nuevo usuario. Asigna rol por defecto (Cliente) y estado 'pendiente'. | Público |
| `auth/checkEmail` | GET | Endpoint AJAX para verificar disponibilidad de email. | Público |

### Perfil de Usuario

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `auth/profile` | GET | Muestra el perfil del usuario y estadísticas personales. | Autenticado |
| `auth/updateProfile` | POST | Actualiza los datos del perfil y contraseña del usuario actual. | Autenticado |

### Recuperación de Contraseña

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `auth/forgotPassword` | GET | Muestra formulario para solicitar reset de contraseña. | Público |
| `auth/sendResetLink` | POST | Genera token y simula el envío (o envía) el link de recuperación. | Público |
| `auth/resetPasswordForm` | GET | Muestra formulario para establecer nueva contraseña (requiere token). | Público |
| `auth/handleResetPassword`| POST | Procesa el cambio de contraseña usando el token. | Público |

## 3. Lógica de Negocio

### Inicio de Sesión
1.  **Validación:** Email y contraseña requeridos.
2.  **Autenticación:** Verifica credenciales contra hash en BD.
3.  **Verificación de Estado:**
    *   `activo`: Permite acceso.
    *   `pendiente`: Deniega acceso (mensaje: cuenta no aprobada).
    *   `inactivo`: Deniega acceso (mensaje: cuenta desactivada).
4.  **Sesión:** Almacena `id`, `nombre`, `email`, `id_rol` en `$_SESSION['user']`.
5.  **Recuérdame:** Si se selecciona, crea cookie encriptada `remember_me` por 30 días.

### Registro
1.  **Validación:**
    *   Campos requeridos.
    *   Cédula formato venezolano (`ve_ci`), única.
    *   Teléfono formato venezolano (`ve_phone`).
    *   Email válido (`email`, `common_email`) y único.
    *   Password min 8 caracteres.
2.  **Creación:** Crea usuario con rol Cliente (1) y estado `pendiente`.
3.  **Resultado:** Redirige a login con mensaje flash.

### Recuperación de Contraseña
1.  **Token:** Genera token aleatorio y expiración (1 hora).
2.  **Persistencia:** Guarda token y expiración en tabla `usuarios`.
3.  **Validación Token:** Al resetear, verifica que el token coincida y no haya expirado (`NOW() < reset_expires`).

## 4. Modelos Relacionados

*   **Usuario (`app/Models/Usuario.php`):**
    *   `authenticate($email, $password)`: Verifica credenciales.
    *   `create($data)`: Inserta nuevo usuario.
    *   `update($id, $data)`: Actualiza perfil.
    *   `getByEmail($email)`, `getById($id)`: Consultas básicas.
    *   `setResetToken(...)` / `getUserByToken(...)`: Gestión de tokens de reset.
    *   `getStats($id)`: Obtiene estadísticas de reservas del usuario.

## 5. Validaciones

Utiliza `core/Validator.php` con reglas como:
*   `required`: Campo obligatorio.
*   `email`, `common_email`: Formato de correo.
*   `ve_ci`, `ve_phone`: Formatos venezolanos.
*   `unique:table,column`: Unicidad en base de datos.
*   `minlen`, `maxlen`: Longitud de cadenas.

## 6. Seguridad

*   **Contraseñas:** Hasheadas usando `Security::hashPassword` (probablemente `password_hash`).
*   **CSRF:** (No explícitamente visto en el controlador, verificar implementación global).
*   **SQL Injection:** Uso de `PDO` y _prepared statements_ en todos los modelos.
*   **Acceso:** Verificación `!empty($_SESSION['user'])` en métodos protegidos.
