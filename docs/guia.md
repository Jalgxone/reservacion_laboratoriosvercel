# Guía de Uso del Sistema

Esta guía describe los procesos operativos clave para interactuar con el sistema de manera eficiente, aprovechando las mejoras de interfaz y usabilidad implementadas.

## 1. Experiencia de Usuario y Feedback
El sistema utiliza un motor de notificaciones tipo **"Pillow Style"**. Estos mensajes aparecen en la parte superior para informarte sobre el éxito o error de tus acciones:
- **Éxito**: Bloques verdes que confirman operaciones guardadas.
- **Error**: Bloques rojos detallados que indican fallos de validación o del sistema.

## 2. Gestión de Laboratorios
En el módulo de "Laboratorios", los administradores pueden gestionar la infraestructura física.
- **Interacción Ágil**: El borrado de laboratorios se realiza mediante tecnología AJAX, eliminando el registro de la vista instantáneamente sin necesidad de recargar la página completa.

## 3. Reservaciones y Disponibilidad
- **Formulario Inteligente**: Al crear una reserva, el sistema valida en tiempo real que las fechas sean coherentes (la fecha de fin debe ser posterior a la de inicio) y que se respeten los horarios de operación configurados.
- **Visualizador de Horarios**: Consulta la disponibilidad semanal de cada sala de forma visual antes de realizar una solicitud.

## 4. Gestión de Inventario
- **Formato Estricto de Hardware**: Al registrar nuevos equipos, el sistema exige el formato oficial `Marca Modelo` (ej. "HP EliteBook"). Esto garantiza la limpieza de la base de datos para futuros reportes de mantenimiento.
- **Paginación Dinámica**: Las tablas de inventario muestran 5 elementos por página, permitiendo una navegación fluida y organizada.

## 5. Reporte de Incidencias
Si detectas un problema con un equipo:
1. Accede a "Reportar Incidencia".
2. Selecciona el equipo afectado.
3. Describe el problema y asigna un **Nivel de Gravedad** (Baja, Media, Alta). Esto permitirá al administrador priorizar las reparaciones.

