# Documentación técnica


Esta documentación describe en detalle la arquitectura interna, las clases núcleo, contratos de controladores y modelos, validación, conexión a BD y ejemplos prácticos para desarrolladores.

**Resumen del proyecto**

- Aplicación MVC ligera en PHP para la reservación de laboratorios, gestión de inventario, recursos y reportes de incidencias.
- Punto de entrada: `public/index.php` → `core/App`.

**Arquitectura y flujo MVC**

1. `public/index.php` recibe la petición y delega a `core/App`.
2. `App::parseUrl()` separa la ruta en segmentos y resuelve `Controller` y `method`.
3. `Controller` carga `Model` con `model($name)` y renderiza vistas con `view($path,$data)`.
4. `Model` obtiene conexión a BD usando `Database::getConnection()` y expone métodos CRUD propios por modelo.

**`core/App`**

- Propiedades por defecto:
  - `$controller = 'AuthController'`
  - `$method = 'index'`
  - `$params = []`
- Constructor: Resuelve archivo de controlador en `app/Controllers/` según el primer segmento de la URL; si existe, instancia la clase y resuelve el método (segundo segmento) y parámetros restantes.
- Método `parseUrl()`: usa `$_GET['url']`, sanitiza y explota por `/`.

**`core/Controller` (API y utilidades)**

- `model(string $model)`:
  - Carga archivo `app/Models/{$model}.php` y retorna `new $model()` o `null` si no existe.
- `view(string $view, array $data = [])`:
  - Carga `app/Views/{$view}.php`. Usa `extract($data)` antes de requerir la vista.
- `currentUser()`:
  - Normaliza la estructura de `$_SESSION['user']` y retorna el usuario o `null`.
- `requireRole(array $allowed = [])`:
  - Valida que haya sesión activa; si `$allowed` está vacío solo verifica autenticación.
  - Si hay roles en `$allowed` compara `id_rol` del usuario y redirige/deniega en caso de fallo.
- `jsonResponse($data, $status = 200)`:
  - Emite JSON y código HTTP.

Implementación típica en controladores: usar `requireRole()` al inicio de acciones que requieran autenticación e invocar `model()` y `view()`.

**`core/Model`**

- Constructor: inicializa `$this->db = Database::getConnection()`.
- Cada modelo (p. ej. `Reserva`, `Laboratorio`, `Usuario`) debe implementar métodos específicos como:
  - `getAll(): array`
  - `getById($id): ?array`
  - `create(array $data): int` (retorna id)
  - `update($id, array $data): bool`
  - `delete($id): bool`

Ejemplo de uso en `ReservasController::store()`:

- Convierte `datetime-local` a formato MySQL: `str_replace('T',' ',$value)` antes de validar/guardar.
- Valida con `Validator::validate($data,$rules)` antes de invocar `$model->create($data)`.

**Conexión a base de datos (`config/database.php`)**

- Clase `Database` con `getConnection()` que crea una instancia `PDO` singleton.
- Parámetros por defecto encontrados en el repo:
  - host: `127.0.0.1`
  - db: `laboratory`
  - user: `root`
  - pass: `` (vacío)
  - charset: `utf8mb4`

Recomendación: mover credenciales sensibles a variables de entorno o fuera del repo para producción.

**Validación (`core/Validator.php`)**

- Reglas soportadas (cadena separada por `|`): `required`, `int`, `datetime`, `email`, `minlen:N`, `maxlen:N`, `unique:table,field`.
- `unique:table,field` ejecuta una consulta para comprobar existencia previa.
- `validate(array $data, array $rules): array` devuelve un arreglo `errors[field] = [msg,...]`.

Ejemplo de reglas usadas en `ReservasController::store()`:

```php
$rules = [
    'id_usuario' => 'required|int',
    'id_laboratorio' => 'required|int',
    'fecha_inicio' => 'required|datetime',
    'fecha_fin' => 'required|datetime',
];
```

**Seguridad y claves (`config/security.php`)**

- Contiene `APP_ENCRYPTION_KEY` usada por utilidades de la app. Debe reemplazarse por una clave fuerte en producción.

**Estructura de tablas (resumen, basándose en `db/Lab.sql`)**

- Tablas principales esperadas:
  - `usuarios` (id, nombre, email, password, id_rol, ...)
  - `laboratorios` (id, nombre, ubicacion, capacidad, ...)
  - `reservas` (id, id_usuario, id_laboratorio, fecha_inicio, fecha_fin, id_estado, motivo_uso)
  - `recursos`, `inventarios`, `incidencias`, `estado_reserva`.

Snippet SQL básico para `reservas`:

```sql
CREATE TABLE reservas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_laboratorio INT NOT NULL,
  fecha_inicio DATETIME NOT NULL,
  fecha_fin DATETIME NOT NULL,
  id_estado INT DEFAULT 1,
  motivo_uso TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Buenas prácticas y recomendaciones para desarrolladores**

- Validación: siempre use `Validator` en `store()` y `update()` para evitar datos inválidos.
- Manejo de errores: envolver operaciones de BD en try/catch y loguear con `error_log()`.
- Acceso a rutas y redirecciones: usar `$_SERVER['SCRIPT_NAME'] . '?url=...'` para mantener rutas relativas al front controller.
- CSRF: actualmente no se ve protección automática; agregar tokens CSRF en formularios para seguridad.
- Sanitización: las vistas usan `extract()` — al imprimir datos en HTML, escapar con `htmlspecialchars()`.

**Ejemplos de código útiles**

- Respuesta JSON desde controlador:

```php
$this->jsonResponse(['ok' => true, 'msg' => 'éxito'], 200);
```

- Cargar modelo y obtener datos:

```php
$labModel = $this->model('Laboratorio');
$labs = $labModel->getAll();
$this->view('reservas/create', ['labs' => $labs]);
```

**Checklist antes de desplegar a producción**

- Cambiar `APP_ENCRYPTION_KEY` por una clave segura y almacenar fuera del repo.
- Mover credenciales de BD a variables de entorno.
- Activar manejo de errores apropiado y desactivar `display_errors`.
- Implementar CSRF y revisar sanitización de salida en vistas.

---

Si quieres, puedo:

- Generar documentación en formato HTML estático a partir de este `.md`.
- Insertar PHPDoc/annotations en los modelos y controladores automáticamente.
- Generar snippets de ejemplo más detallados (métodos por modelo), para eso leeré cada modelo y autogeneraré su API.
