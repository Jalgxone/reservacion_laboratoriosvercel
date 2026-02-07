# Guía de Instalación

Sigue estos pasos para poner en marcha el sistema en tu entorno local.

## Requisitos Previos
- **PHP 8.0+**
- **MySQL / MariaDB**
- **XAMPP** (recomendado para Windows)
- **Composer** (para gestión de dependencias)

## Paso 1: Clonar o Descargar
Descarga el código fuente en tu carpeta de servidor (ej. `C:/xampp/htdocs/reservacion_laboratorios`).

## Paso 2: Configuración de Base de Datos
1. Abre **phpMyAdmin**.
2. Crea una base de datos llamada `laboratory`.
3. Importa el archivo SQL ubicado en `db/Lab.sql`.

## Paso 3: Configuración del Sistema
Edita el archivo `config/database.php` con tus credenciales:

```php
$host = '127.0.0.1';
$db   = 'laboratory';
$user = 'root';
$pass = ''; // Tu contraseña de MySQL
```

## Paso 4: Instalación de Dependencias
Abre una terminal en la raíz del proyecto y ejecuta:
```bash
composer install
```

## Paso 5: Acceso
Abre tu navegador y dirígete a:
`http://localhost/reservacion_laboratorios/public/`

---
> [!IMPORTANT]
> Asegúrate de que el módulo `mod_rewrite` de Apache esté activado para que las rutas amigables funcionen correctamente.
