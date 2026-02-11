# Módulo de Reservas

El módulo de Reservas es el núcleo del sistema, permitiendo a los usuarios solicitar espacios en los laboratorios y a los administradores gestionar dichas solicitudes.

## 1. Descripción General

**Controlador:** `ReservasController`
**Modelos:** `Reserva`, `Laboratorio`, `EstadoReserva`, `Usuario`

Este módulo es responsable de:
- Permitir a los usuarios crear nuevas solicitudes de reserva.
- Validar la disponibilidad de horarios y laboratorios (prevención de solapamientos).
- Gestionar el ciclo de vida de una reserva (Pendiente -> Confirmada/Cancelada).
- Encriptar información sensible (motivo del uso).

## 2. Endpoints

El acceso requiere autenticación. Los permisos varían según el rol (Usuario vs Administrador).

| Ruta | Método | Descripción | Permisos |
| :--- | :--- | :--- | :--- |
| `reservas` | GET | Lista las reservas (historial). | Autenticado |
| `reservas/create` | GET | Muestra formulario de solicitud. | Autenticado |
| `reservas/store` | POST | Procesa la creación de la reserva. | Autenticado |
| `reservas/edit/{id}` | GET | Muestra formulario de edición. | Dueño o Admin |
| `reservas/update/{id}` | POST | Actualiza la reserva. | Dueño o Admin |
| `reservas/delete/{id}` | GET | Elimina una reserva. | Dueño o Admin |

## 3. Lógica de Negocio

### Reglas de Acceso
-   **Clientes:** Solo pueden ver, editar o eliminar sus propias reservas.
-   **Administradores:** Tienen acceso total a todas las reservas y pueden cambiar el estado (ej: aprobar/rechazar).

### Estados de Reserva
1.  **Pendiente:** Estado inicial por defecto al crear.
2.  **Confirmada:** Aprobada por un administrador.
3.  **Cancelada:** Rechazada o cancelada.

### Prevención de Conflictos
El modelo `Reserva` implementa el método `hasOverlap($labId, $start, $end)` que verifica en la base de datos si existe alguna reserva activa que coincida temporalmente con el intervalo solicitado para el mismo laboratorio.

### Seguridad de Datos
El campo "Motivo de uso" se almacena **encriptado** en la base de datos utilizando la clase `Security` (OpenSSL) para privacidad del usuario. Se desencripta automáticamente al visualizar o editar.

## 4. Modelos Relacionados

*   **Reserva (`app/Models/Reserva.php`):**
    *   `create($data)`: Inserta reserva y valida solapamientos.
    *   `update($id, $data)`: Actualiza y valida solapamientos (excluyendo la propia reserva).
    *   `getSchedule($lab, $start, $end)`: Utilizado por el módulo de Horarios.
    *   `hasOverlap(...)`: Lógica crítica de disponibilidad.

## 5. Validaciones

*   **Laboratorio:** Debe existir y estar marcado como activo.
*   **Fechas:**
    -   Formato datetime válido.
    -   Fecha inicio debe ser futura.
    -   Fecha fin debe ser posterior a fecha inicio.
    -   **Horario Laboral:** Las reservas están restringidas entre las 07:00 AM y 09:00 PM (validadores `business_hours`).
*   **Motivo:** Requerido, mínimo 5 caracteres.

## 6. Seguridad

*   **Propiedad:** Verificación explícita de `$_SESSION['user_id'] == $reserva['id_usuario']` para evitar acceso no autorizado a reservas ajenas.
*   **Encriptación:** Datos del motivo protegidos en reposo.
