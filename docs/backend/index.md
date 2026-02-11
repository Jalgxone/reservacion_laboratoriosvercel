# Documentación Técnica del Backend

Bienvenido a la documentación técnica del backend del **Sistema de Reservación de Laboratorios**.

## Arquitectura

El sistema está construido sobre una arquitectura **MVC (Modelo-Vista-Controlador)** personalizada en PHP nativo, sin el uso de frameworks de terceros, para mantener un núcleo ligero y optimizado.

### Componentes Principales

*   **Core:**
    *   `App.php`: Enrutador principal.
    *   `Controller.php`: Clase base para todos los controladores.
    *   `Model.php`: Clase base para los modelos, gestiona la conexión a BD.
    *   `Database.php`: Wrapper Singleton para PDO.
    *   `Validator.php`: Biblioteca de validación de datos.
    *   `Security.php`: Utilidades de encriptación y hashing.

## Módulos del Sistema

La lógica de negocio se divide en 8 módulos principales. Haga clic en cada uno para ver su documentación detallada:

1.  [**Autenticación**](autenticacion.md) (`AuthController`)
    *   Login, registro, recuperación de contraseña y gestión de perfil.

2.  [**Usuarios**](usuarios.md) (`UsuariosController`)
    *   Gestión de cuentas, roles y permisos de acceso.

3.  [**Laboratorios**](laboratorios.md) (`LaboratoriosController`)
    *   Administración de los espacios físicos disponibles.

4.  [**Recursos**](recursos.md) (`RecursosController`)
    *   Gestión de las categorías de equipos (ej: Laptops, Proyectores).

5.  [**Reservas**](reservas.md) (`ReservasController`)
    *   Núcleo del sistema: Solicitud y validación de préstamos de laboratorios.

6.  [**Horarios**](horarios.md) (`HorariosController`)
    *   Visualización del calendario semanal de ocupación.

7.  [**Incidencias**](incidencias.md) (`IncidenciasController`)
    *   Reporte y seguimiento de fallos en los equipos.

8.  [**Inventarios**](inventarios.md) (`InventariosController`)
    *   Registro físico de equipos y control de activos.
