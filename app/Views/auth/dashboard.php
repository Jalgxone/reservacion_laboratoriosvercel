<?php
$title = "Dashboard | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Panel de Control</h1>
        <p class="pagina-subtitulo">Bienvenido de nuevo, <strong><?= htmlspecialchars($user['nombre'] ?? 'Usuario') ?></strong></p>
    </div>
    <div class="acciones">
        <span class="badge badge-info" style="padding: var(--espacio-sm) var(--espacio-md);">
            ID de Usuario: <?= htmlspecialchars($user['id'] ?? 'N/A') ?>
        </span>
    </div>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--espacio-lg);">
    <!-- Módulo Laboratorios -->
    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios" class="card" style="text-decoration: none; padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
        <div style="background: rgba(14, 165, 233, 0.05); padding: var(--espacio-lg); border-bottom: 1px solid var(--color-borde); display: flex; align-items: center; gap: var(--espacio-md);">
            <div style="background: var(--color-superficie); width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; box-shadow: var(--sombra-sm); color: var(--color-acento);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
            </div>
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--color-primario);">Laboratorios</h3>
        </div>
        <div style="padding: var(--espacio-lg); flex-grow: 1; display: flex; flex-direction: column;">
            <p style="color: var(--color-texto-claro); font-size: 0.95rem; margin-bottom: var(--espacio-lg); line-height: 1.5;">Administre las salas de laboratorio, su capacidad técnica y disponibilidad en tiempo real.</p>
            <div style="margin-top: auto;">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between;">
                    Gestionar Salas
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>

    <!-- Módulo Horarios -->
    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=horarios" class="card" style="text-decoration: none; padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
        <div style="background: rgba(16, 185, 129, 0.05); padding: var(--espacio-lg); border-bottom: 1px solid var(--color-borde); display: flex; align-items: center; gap: var(--espacio-md);">
            <div style="background: var(--color-superficie); width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; box-shadow: var(--sombra-sm); color: var(--color-exito);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--color-primario);">Calendario</h3>
        </div>
        <div style="padding: var(--espacio-lg); flex-grow: 1; display: flex; flex-direction: column;">
            <p style="color: var(--color-texto-claro); font-size: 0.95rem; margin-bottom: var(--espacio-lg); line-height: 1.5;">Consulte la programación semanal y reserve horarios libres para sus actividades.</p>
            <div style="margin-top: auto;">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between;">
                    Ver Calendario
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>

    <!-- Módulo Reservas -->
    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas" class="card" style="text-decoration: none; padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
        <div style="background: rgba(14, 165, 233, 0.05); padding: var(--espacio-lg); border-bottom: 1px solid var(--color-borde); display: flex; align-items: center; gap: var(--espacio-md);">
            <div style="background: var(--color-superficie); width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; box-shadow: var(--sombra-sm); color: var(--color-acento);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
            </div>
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--color-primario);">Reservas</h3>
        </div>
        <div style="padding: var(--espacio-lg); flex-grow: 1; display: flex; flex-direction: column;">
            <p style="color: var(--color-texto-claro); font-size: 0.95rem; margin-bottom: var(--espacio-lg); line-height: 1.5;">Gestión completa de solicitudes de reserva, aprobaciones y control de asistencia.</p>
            <div style="margin-top: auto;">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between;">
                    Administrar
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>

    <!-- Módulo Inventario -->
    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios" class="card" style="text-decoration: none; padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
        <div style="background: rgba(245, 158, 11, 0.05); padding: var(--espacio-lg); border-bottom: 1px solid var(--color-borde); display: flex; align-items: center; gap: var(--espacio-md);">
            <div style="background: var(--color-superficie); width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; box-shadow: var(--sombra-sm); color: var(--color-advertencia);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><polyline points="3.29 7 12 12 20.71 7"></polyline><line x1="12" y1="22" x2="12" y2="12"></line></svg>
            </div>
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--color-primario);">Inventario</h3>
        </div>
        <div style="padding: var(--espacio-lg); flex-grow: 1; display: flex; flex-direction: column;">
            <p style="color: var(--color-texto-claro); font-size: 0.95rem; margin-bottom: var(--espacio-lg); line-height: 1.5;">Control detallado de equipos, periféricos y materiales asignados a cada sala.</p>
            <div style="margin-top: auto;">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between;">
                    Gestionar Equipos
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>

    <!-- Módulo Incidencias -->
    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=incidencias" class="card" style="text-decoration: none; padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
        <div style="background: rgba(239, 68, 68, 0.05); padding: var(--espacio-lg); border-bottom: 1px solid var(--color-borde); display: flex; align-items: center; gap: var(--espacio-md);">
            <div style="background: var(--color-superficie); width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; box-shadow: var(--sombra-sm); color: var(--color-peligro);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--color-primario);">Incidencias</h3>
        </div>
        <div style="padding: var(--espacio-lg); flex-grow: 1; display: flex; flex-direction: column;">
            <p style="color: var(--color-texto-claro); font-size: 0.95rem; margin-bottom: var(--espacio-lg); line-height: 1.5;">Reporte de fallas técnicas y seguimiento del estado de reparación de los recursos.</p>
            <div style="margin-top: auto;">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between;">
                    Reportar Falla
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>

    <!-- Módulo Recursos -->
    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos" class="card" style="text-decoration: none; padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
        <div style="background: rgba(14, 165, 233, 0.05); padding: var(--espacio-lg); border-bottom: 1px solid var(--color-borde); display: flex; align-items: center; gap: var(--espacio-md);">
            <div style="background: var(--color-superficie); width: 40px; height: 40px; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; box-shadow: var(--sombra-sm); color: var(--color-acento);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
            </div>
            <h3 style="margin: 0; font-size: 1.1rem; color: var(--color-primario);">Recursos</h3>
        </div>
        <div style="padding: var(--espacio-lg); flex-grow: 1; display: flex; flex-direction: column;">
            <p style="color: var(--color-texto-claro); font-size: 0.95rem; margin-bottom: var(--espacio-lg); line-height: 1.5;">Configuración de tipos de recursos, categorías y parámetros generales del sistema.</p>
            <div style="margin-top: auto;">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between;">
                    Configurar
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
