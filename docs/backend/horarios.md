# Módulo de Horarios

El módulo de Horarios proporciona una vista de calendario semanal para visualizar la ocupación de los laboratorios.

## 1. Descripción General

**Controlador:** `HorariosController`
**Modelos:** `Laboratorio`, `Reserva`

Este módulo es responsable de:
- Calcular los rangos de fechas para la visualización semanal (Domingo a Sábado).
- Filtrar las reservas por laboratorio y fecha.
- Proveer una interfaz de navegación entre semanas.
- Soportar carga asíncrona (AJAX) para actualizaciones dinámicas del calendario.

## 2. Endpoints

El acceso requiere autenticación básica.

| Ruta | Método | Descripción | Parámetros |
| :--- | :--- | :--- | :--- |
| `horarios` | GET | Muestra el calendario o retorna JSON si es AJAX. | `lab` (ID laboratorio), `date` (YYYY-MM-DD) |

## 3. Lógica de Negocio

### Cálculo de Semanas
El sistema toma una fecha de referencia (por defecto `hoy` o el parámetro `date`) y calcula el inicio de la semana (Domingo) y el fin de la semana (Sábado + 6 días).

### Navegación
Genera automáticamente las fechas para los enlaces de "Semana Anterior" (-1 semana) y "Semana Siguiente" (+1 semana).

### Respuesta Dual (HTML/JSON)
El controlador detecta si la petición es AJAX (`HTTP_X_REQUESTED_WITH` = `xmlhttprequest`).
-   **Petición Normal:** Renderiza la vista `horarios/index` con todo el layout.
-   **Petición AJAX:** Retorna un objeto JSON con las reservas, laboratorios y fechas calculadas, permitiendo actualizar el DOM sin recargar la página completa.

## 4. Modelos Relacionados

*   **Laboratorio:** Obtiene la lista para el selector de laboratorios.
*   **Reserva:** Utiliza el método `getSchedule($labId, $start, $end)` para obtener las reservas que caen dentro del rango visualizado.

## 5. Validaciones

*   **Sesión:** Verifica `!empty($_SESSION['user'])`.
*   **Laboratorio por defecto:** Si no se especifica un laboratorio, selecciona automáticamente el primero de la lista.

## 6. Seguridad

*   Solo usuarios autenticados pueden ver la disponibilidad detallada.
