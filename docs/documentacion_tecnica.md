# Documentación Técnica: Sistema de Reservación de Laboratorios

Esta documentación proporciona una visión profunda y detallada de la arquitectura, lógica de negocio y estándares de desarrollo del proyecto. Está diseñada para servir como guía completa para desarrolladores que deseen mantener o extender el sistema.

---

## 1. Arquitectura del Sistema (MVC Freelance)

El proyecto sigue un patrón **Model-View-Controller (MVC)** desacoplado, diseñado para ser ligero pero altamente funcional sin dependencias pesadas de frameworks externos.

### 1.1 El Núcleo (`core/`)
El corazón del sistema reside en el directorio `core/`, el cual gestiona el ciclo de vida de cada petición.

- **`App.php` (Enrutador):**
  - Actúa como el *Front Controller*. Su responsabilidad es procesar la URL (usando `$_GET['url']`), limpiarla y descomponerla.
  - Resuelve dinámicamente el controlador solicitado, instancia el objeto y llama al método correspondiente pasando los parámetros detectados.
  - **Punto de Entrada:** `public/index.php`.

- **`Controller.php` (Base):**
  - Todos los controladores en `app/Controllers/` heredan de esta clase.
  - Proporciona métodos esenciales:
    - `model($name)`: Carga e instancia modelos de datos.
    - `view($path, $data)`: Extrae variables y renderiza una vista.
    - `requireRole($roles)`: Middleware de seguridad para proteger rutas.
    - `jsonResponse($data, $status)`: Estandariza las respuestas del API.

- **`Model.php` (Datos):**
  - Clase base que inicializa la conexión a la base de datos vía PDO.
  - Implementa un acceso singleton a la base de datos para optimizar recursos.

---

## 2. Sistema de Validación (`Validator.php`)

El validador es uno de los componentes más robustos del sistema. Permite definir reglas complejas de manera declarativa para garantizar la integridad de los datos antes de que lleguen a la base de datos.

### 2.1 Reglas Disponibles

| Regla | Descripción | Ejemplo de Uso |
| :--- | :--- | :--- |
| `required` | El campo no puede estar vacío o ser nulo. | `'nombre' => 'required'` |
| `int` | Fuerza a que el valor sea un entero válido. | `'capacidad' => 'int'` |
| `email` | Valida el formato de correo electrónico estándar. | `'email' => 'email'` |
| `common_email` | Restringe a proveedores conocidos (Gmail, Outlook, Yahoo, etc.). | `'email' => 'common_email'` |
| `ve_ci` | Formato de Cédula de Identidad Venezolana (Ej: V-12345678). | `'cedula' => 've_ci'` |
| `ve_phone` | Teléfono venezolano (+58 o 0 seguido de 10 dígitos). | `'telefono' => 've_phone'` |
| `proper_brand_format` | Formato estricto 'Marca Modelo' (Ej: 'Dell Latitude'). | `'marca_modelo' => 'proper_brand_format'` |
| `minlen:N` / `maxlen:N` | Establece límites de longitud de caracteres. | `'password' => 'minlen:8'` |
| `min_val:N` / `max_val:N` | Establece límites numéricos mínimos o máximos. | `'capacidad' => 'min_val:10'` |
| `business_hours:H1:H2` | Restringe la hora a un rango específico. | `'fecha' => 'business_hours:08:18'` |
| `future` | El valor debe ser una fecha/hora posterior a la actual. | `'fecha_inicio' => 'future'` |
| `after_field:F` | La fecha debe ser mayor a la del campo `F`. | `'fecha_fin' => 'after_field:fecha_inicio'` |
| `in_list:A,B,C` | El valor debe estar contenido en la lista proporcionada. | `'estado' => 'in_list:Activo,Inactivo'` |
| `unique:T,C,ID_C,ID_V` | Valida unicidad en la tabla `T` columna `C`, excluyendo un ID si es edición. | `'email' => 'unique:usuarios,email'` |

---

## 3. Seguridad y Gestión de Sesiones

### 3.1 Roles y Permisos
El sistema utiliza un esquema de roles basado en enteros:
- **Rol 1 (Usuario):** Puede ver laboratorios, realizar reservas y reportar incidencias.
- **Rol 2 (Administrador):** Control total sobre inventario, usuarios, configuración de laboratorios y reportes.

### 3.2 Protección de Rutas
Se utiliza el método `$this->requireRole([2])` al inicio de los controladores o métodos específicos. Si el usuario no cumple el requisito, es redirigido automáticamente.

### 3.3 Lógica Code-Safe
En el `UsuariosController`, existen protecciones críticas para evitar desastres operativos:
- **Protección de Cuenta Propia:** Un administrador no puede quitarse a sí mismo el privilegio de administrador ni desactivar su propia cuenta. Esto garantiza que siempre haya al menos un acceso administrativo al sistema.

---

## 4. Lógica de Inventario y Reservas

El sistema implementa reglas de negocio estrictas para mantener la coherencia entre el inventario físico y la disponibilidad de los laboratorios:

- **Movimiento de Equipos:** No se permite mover un equipo de un laboratorio a otro si el laboratorio de origen tiene reservas activas.
- **Baja de Equipos:** Un equipo no puede ser dado de baja si el laboratorio donde se encuentra tiene reservas vigentes, asegurando que los recursos prometidos para una sesión estén disponibles.

---

## 5. Interfaz de Usuario (Frontend)

### 5.1 Sistema de Alertas (Pillow Style)
El sistema utiliza bloques de alerta estáticos (localizados en `app/Views/partials/alerts.php`) que proporcionan feedback visual inmediato:
- **Éxito:** Fondo verde suave, desaparece automáticamente después de 5 segundos.
- **Error:** Fondo rojo pastel, persiste para que el usuario pueda corregir los datos.
- **Advertencia/Info:** Colores amarillo y azul respectivamente.

### 5.2 Estética y Estilos
El diseño se basa en un sistema de variables CSS (`public/css/variables.css`) que centraliza la paleta de colores, espaciados y sombras. Esto facilita cambios globales de diseño (como un modo oscuro) sin tocar múltiples archivos.

### 5.3 Funcionalidad Dinámica
- **Paginación del Cliente:** Se incluye un script de paginación dinámica (`initPagination`) en el header que permite manejar tablas largas sin recargar la página, mejorando la fluidez de la navegación.
- **Menús Contextuales:** Dropdowns interactivos para el perfil del usuario administrados mediante JavaScript nativo.


## 5.4 Especificación de Vistas (Frontend)

### Vistas de Autenticación

- **Login (`auth/login.php`)**  
Permite a los usuarios acceder al sistema mediante correo y contraseña. Presenta un formulario moderno, con validación de campos y mensajes de error contextualizados. El fondo utiliza una imagen institucional y el logo, reforzando la identidad visual.  
- *Campos*: Email, Contraseña  
- *Acciones*: Iniciar sesión, enlace a recuperación de contraseña  
- *Feedback*: Errores de validación y alertas visuales

- **Registro (`auth/register.php`)**  
Facilita la creación de nuevas cuentas. El formulario solicita nombre, apellido, email, cédula, teléfono y contraseña, con validaciones estrictas.  
- *Campos*: Nombre, Apellido, Email, Cédula, Teléfono, Contraseña, Confirmación  
- *Acciones*: Registrar usuario  
- *Feedback*: Mensajes de error por campo, alertas de éxito o fallo

- **Recuperar Contraseña (`auth/forgot_password.php`)**  
Permite solicitar un enlace de recuperación vía email. El diseño es similar al login, con énfasis en la seguridad y facilidad de uso.  
- *Campos*: Email  
- *Acciones*: Enviar enlace de recuperación  
- *Feedback*: Mensajes de confirmación o error

- **Restablecer Contraseña (`auth/reset_password.php`)**  
Vista para establecer una nueva contraseña tras recibir el enlace de recuperación.  
- *Campos*: Nueva contraseña, Confirmar contraseña  
- *Acciones*: Actualizar contraseña  
- *Feedback*: Validación de coincidencia y fortaleza

- **Perfil (`auth/profile.php`)**  
Muestra información personal del usuario, estadísticas de reservas y permite actualizar datos básicos.  
- *Campos*: Nombre, Apellido, Email, Teléfono  
- *Acciones*: Editar perfil  
- *Feedback*: Mensajes de éxito/error, estadísticas visuales

- **Dashboard (`auth/dashboard.php`)**  
Panel de inicio tras autenticación. Resume accesos rápidos a laboratorios, horarios, reservas, inventario y usuarios según el rol.  
- *Elementos*: Tarjetas de navegación, bienvenida personalizada  
- *Acciones*: Acceso a módulos principales


### Vistas de Gestión

- **Laboratorios (`laboratorios/index.php`)**  
Lista todos los laboratorios registrados, mostrando nombre, ubicación, capacidad y estado. Los administradores pueden crear, editar o eliminar laboratorios.  
- *Acciones*: Crear, editar, eliminar laboratorio  
- *Feedback*: Mensajes de confirmación y error

- **Inventario (`inventarios/index.php`)**  
Presenta el inventario de equipos, con filtros por nombre, estado y laboratorio. Permite registrar nuevos equipos, editar y dar de baja.  
- *Acciones*: Crear, editar, filtrar, dar de baja  
- *Feedback*: Badges de estado, alertas visuales

- **Recursos (`recursos/index.php`)**  
Gestiona categorías de equipos y recursos. Muestra cantidad, estado y observaciones.  
- *Acciones*: Crear, editar, eliminar categoría  
- *Feedback*: Badges de cantidad y estado

- **Incidencias (`incidencias/index.php`)**  
Listado de fallas reportadas en equipos. Permite registrar nuevas incidencias, editar y cambiar estado.  
- *Acciones*: Crear, editar, cerrar incidencia  
- *Feedback*: Tabla detallada, badges de gravedad y estado

- **Horarios (`horarios/index.php`)**  
Muestra un calendario semanal con la disponibilidad de laboratorios. Permite filtrar por laboratorio y navegar entre semanas.  
- *Acciones*: Visualizar disponibilidad, filtrar, navegar  
- *Feedback*: Calendario interactivo, colores por estado

- **Reservas (`reservas/index.php`)**  
Listado de reservas realizadas por el usuario o administrador. Muestra laboratorio, fechas, usuario, estado y motivo.  
- *Acciones*: Crear, editar, cancelar reserva  
- *Feedback*: Badges de estado, historial visual

- **Usuarios (`usuarios/index.php`)**  
Gestión de usuarios registrados. Permite crear, editar, activar/desactivar y asignar roles.  
- *Acciones*: Crear, editar, cambiar estado, asignar rol  
- *Feedback*: Tabla segmentada por rol, badges de estado

Cada vista está diseñada para ser intuitiva, accesible y visualmente consistente, utilizando componentes reutilizables y estilos centralizados. Los formularios implementan validaciones tanto del lado del cliente como del servidor, garantizando la integridad de los datos y la experiencia del usuario.


