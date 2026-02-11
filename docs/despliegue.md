# Despliegue en la Nube

Una vez que el sistema está listo en el entorno local, el siguiente paso es hacerlo accesible de manera global. Esta sección guía este proceso utilizando las mejores herramientas disponibles.

## Plataformas Sugeridas

### 1. Vercel (Ideal para la Documentación)
Vercel es la plataforma recomendada para alojar esta documentación generada con MkDocs debido a su proceso de despliegue automatizado y alto rendimiento.
- **Flujo**: Sube los cambios a GitHub. Vercel detectará el archivo `mkdocs.yml`, ejecutará el build y te entregará una URL profesional de forma gratuita.

### 2. Hosting Tradicional (Para la Aplicación PHP)
Dado que el sistema requiere soporte para PHP 8.2 y MySQL, se recomienda el uso de hostings compartidos profesionales o VPS (Virtual Private Servers).
- **Consideración Crítica**: El servidor de destino debe permitir la ejecución de `composer` y tener activado el módulo `mod_rewrite` para que el enrutamiento funcione correctamente.

## Recomendaciones para Producción

> [Ojo]
> Antes de pasar a producción, asegúrate de realizar los siguientes ajustes de seguridad:

1. **Llaves de Encriptación**: Cambia la `APP_ENCRYPTION_KEY` por una cadena aleatoria y segura.
2. **Entorno de Datos**: No utilices las credenciales de base de datos predeterminadas (`root`, sin contraseña).
3. **Manejo de Errores**: Desactiva el modo de depuración (`display_errors`) para evitar mostrar información sensible del sistema a los usuarios finales.
4. **Protección de Archivos**: Configura correctamente los permisos de las carpetas para que solo los archivos de la carpeta `public/` sean accesibles directamente desde el navegador.

## Notas sobre el uso de clases en inglés en el backend

El uso de nombres de clases y modelos en inglés en el backend responde a las siguientes razones:

- **Estándar internacional**: El inglés es el idioma predominante en la documentación técnica y en la mayoría de frameworks y librerías, lo que facilita la integración y el mantenimiento del código.
- **Compatibilidad y escalabilidad**: Usar inglés permite que el proyecto sea más accesible para desarrolladores de diferentes países y facilita la colaboración internacional.
- **Buenas prácticas**: Seguir convenciones globales mejora la comprensión del código y su interoperabilidad con otras herramientas y servicios.

Esta decisión busca asegurar la calidad, mantenibilidad y proyección futura del sistema.