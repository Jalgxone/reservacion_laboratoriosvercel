# Modernización y Arquitectura

El sistema ha evolucionado de una estructura tradicional a una aplicación web moderna y fluida.

## Arquitectura MVC
El proyecto sigue el patrón **Modelo-Vista-Controlador**:
- **Modelos**: Gestión de datos y lógica de base de datos (`app/Models`).
- **Vistas**: Plantillas PHP dinámicas e interactivas (`app/Views`).
- **Controladores**: Orquestación de peticiones y respuestas (`app/Controllers`).

## Mejoras AJAX y Fetch
Se han integrado las siguientes funcionalidades asíncronas:

### 1. Borrado en Caliente
Se eliminan registros sin recargar la página mediante Fetch API.
```javascript
fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(data => window.showToast(data.message, 'success'));
```

### 2. Calendario Dinámico
El módulo de horarios se actualiza instantáneamente al cambiar parámetros, redibujando el DOM progresivamente.

### 3. Filtros y Búsqueda en Tiempo Real
El inventario utiliza lógica de cliente para filtrar cientos de equipos en milisegundos sin latencia de red.

## Sistema de Notificaciones (Toasts)
Se implementó un motor de alertas global que:
- Detecta mensajes flash de PHP automáticamente.
- Proporciona feedback visual profesional y no intrusivo.
