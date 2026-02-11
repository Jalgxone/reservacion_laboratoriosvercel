# Seguridad y Roles

La seguridad es un pilar fundamental en este sistema. Se implementan múltiples capas de protección para salvaguardar la integridad de los datos y el acceso a las funciones administrativas.

## Control de Acceso Basado en Roles (RBAC)

El sistema distingue entre dos perfiles principales, cada uno con un alcance definido:

| Rol | Alcance Técnico | Funciones Críticas |
|:---|:---|:---|
| **Administrador** | Nivel 2 | Gestión completa de usuarios, inventario, laboratorios y resolución de incidencias. |
| **Cliente** | Nivel 1 | Consulta de disponibilidad, gestión de reservas personales y reporte de fallas. |

## Medidas de Protección Implementadas

### 1. Gestión de Identidad Proactiva
- **Cifrado de Alta Seguridad**: Las contraseñas nunca se almacenan en texto plano; se utiliza `password_hash()` con el algoritmo BCRYPT de última generación.
- **Protección de Sesiones**: Cada solicitud es validada contra la sesión activa del usuario para prevenir secuestros de sesión.

### 2. Integridad de Datos (Input Validation)
- **Validador Centralizado**: Se utiliza un motor de validación estricto para toda entrada de datos, incluyendo:
  - Formatos específicos para Cédula de Identidad y Teléfonos (Venezuela).
  - Restricciones de proveedores de correo autorizados.
  - Validación de unicidad de registros en tiempo real.

### 3. Seguridad a Nivel de Base de Datos
- **Prevención de SQLi**: El uso exclusivo de **PDO con Sentencias Preparadas** elimina el riesgo de ataques por inyección SQL, asegurando que los inputs del usuario nunca se ejecuten como código.

### 4. Protecciones Administrativas (Anti Self-Lockout)
- El sistema cuenta con lógica preventiva para evitar que un administrador se desactive a sí mismo o se retire sus propios privilegios, garantizando que el sistema nunca quede sin un gestor.

