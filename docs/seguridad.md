# Seguridad y Roles

El sistema implementa un modelo de seguridad robusto basado en roles y sesiones.

## Roles de Usuario

| Rol | Descripción | Permisos Clave |
|---|---|---|
| **Administrador** | Personal con control total. | Gestionar inventario, laboratorios, usuarios e incidencias. |
| **Estudiante/Cliente** | Usuario final del servicio. | Realizar reservas, ver horarios y reportar incidencias. |

## Medidas de Seguridad

### 1. Autenticación Proactiva
- Las contraseñas se almacenan mediante `password_hash()` con el algoritmo BCRYPT.
- Se implementan tokens únicos para la recuperación de contraseñas con expiración temporal.

### 2. Control de Acceso (RBAC)
- Cada controlador verifica la sesión del usuario antes de ejecutar acciones sensibles.
- Las vistas ocultan botones o enlaces (ej. "Eliminar") basándose en el ID del rol en la sesión.

### 3. Protección de Datos (PDO)
- Todas las consultas a la base de datos utilizan **Sentencias Preparadas** de PDO para prevenir ataques de **Inyección SQL**.

### 4. Llaves de Aplicación
- El archivo `config/security.php` contiene la llave `APP_ENCRYPTION_KEY` utilizada para procesos internos de cifrado.

