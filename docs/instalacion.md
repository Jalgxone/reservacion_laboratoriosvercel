# Guía de Instalación

Sigue estos pasos estructurados para desplegar el sistema en tu entorno de desarrollo local de manera exitosa.

## Requisitos Previos

- **PHP 8.2+**: Asegúrate de tener habilitadas las extensiones `pdo_mysql` y `mbstring`.
- **MySQL**: Motor de base de datos para la persistencia.
- **XAMPP**: Recomendados para entornos Windows.
- **Composer**: Necesario para instalar las dependencias de backend.

## Paso 1: Preparación del Directorio
Clona o descarga el proyecto dentro de la carpeta raíz de tu servidor web:
`C:/xampp/htdocs/reservacion_laboratorios`

## Paso 2: Despliegue de Base de Datos
1. Accede a tu gestor de base de datos (ej. **phpMyAdmin**).
2. Crea una base de datos nueva con el nombre `laboratory`.
3. Importa el script SQL que se encuentra en: `db/Lab.sql`.

## Paso 3: Configuración de Conexiones
Localiza el archivo `app/config/database.php` (o similar según tu estructura) y ajusta las credenciales de acceso:

```php
$host = '127.0.0.1';
$db   = 'laboratory';
$user = 'root';
$pass = ''; // Tu contraseña de base de datos
```

## Paso 4: Instalación de Dependencias
Abre una terminal en la carpeta raíz del proyecto y ejecuta:
```bash
composer install
```

## Paso 5: Verificación del Servidor Web
Asegúrate de que el módulo **`mod_rewrite`** de Apache esté **activado**. Esto es crítico para el correcto funcionamiento del enrutamiento MVC.

## Paso 6: Acceso al Sistema
Abre tu navegador y entra en:
`http://localhost/reservacion_laboratorios/`
