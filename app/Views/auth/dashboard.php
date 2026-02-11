<?php
$title = "Inicio";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Panel de Control</h1>
        <p class="pagina-subtitulo">Bienvenido de nuevo, <strong><?= htmlspecialchars($user['nombre'] ?? 'Usuario') ?></strong></p>
    </div>
</div>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));">
    <?php if (isset($user['id_rol']) && $user['id_rol'] == 2): ?>
    <a href="<?= $appRoot ?>/laboratorios" class="card dashboard-card" style="border-bottom-color: var(--color-acento);">
        <div class="dashboard-card-header" style="background: rgba(14, 165, 233, 0.03);">
            <div class="dashboard-card-icon" style="background: white; color: var(--color-acento);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line></svg>
            </div>
            <h3 class="dashboard-card-title">Laboratorios</h3>
        </div>
        <div class="dashboard-card-body">
            <p class="dashboard-card-text">Administre las salas de laboratorio, su capacidad técnica y disponibilidad en tiempo real.</p>
            <div class="dashboard-card-footer">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between; border-color: rgba(14, 165, 233, 0.2);">
                    Gestionar Salas
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>
    <?php endif; ?>

    <a href="<?= $appRoot ?>/horarios" class="card dashboard-card" style="border-bottom-color: var(--color-exito);">
        <div class="dashboard-card-header" style="background: rgba(16, 185, 129, 0.03);">
            <div class="dashboard-card-icon" style="background: white; color: var(--color-exito);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <h3 class="dashboard-card-title">Horarios</h3>
        </div>
        <div class="dashboard-card-body">
            <p class="dashboard-card-text">Consulte la programación semanal y reserve horarios libres para sus actividades.</p>
            <div class="dashboard-card-footer">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between; border-color: rgba(16, 185, 129, 0.2);">
                    Ver Horarios
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>

    <a href="<?= $appRoot ?>/reservas" class="card dashboard-card" style="border-bottom-color: var(--color-acento);">
        <div class="dashboard-card-header" style="background: rgba(14, 165, 233, 0.03);">
            <div class="dashboard-card-icon" style="background: white; color: var(--color-acento);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
            </div>
            <h3 class="dashboard-card-title">Reservas</h3>
        </div>
        <div class="dashboard-card-body">
            <p class="dashboard-card-text">Gestión completa de solicitudes de reserva, aprobaciones y control de asistencia.</p>
            <div class="dashboard-card-footer">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between; border-color: rgba(14, 165, 233, 0.2);">
                    Administrar
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>

    <?php if (isset($user['id_rol']) && $user['id_rol'] == 2): ?>
    <a href="<?= $appRoot ?>/inventarios" class="card dashboard-card" style="border-bottom-color: var(--color-advertencia);">
        <div class="dashboard-card-header" style="background: rgba(245, 158, 11, 0.03);">
            <div class="dashboard-card-icon" style="background: white; color: var(--color-advertencia);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><polyline points="3.29 7 12 12 20.71 7"></polyline><line x1="12" y1="22" x2="12" y2="12"></line></svg>
            </div>
            <h3 class="dashboard-card-title">Inventario</h3>
        </div>
        <div class="dashboard-card-body">
            <p class="dashboard-card-text">Control detallado de equipos, periféricos y materiales asignados a cada sala.</p>
            <div class="dashboard-card-footer">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between; border-color: rgba(245, 158, 11, 0.2);">
                    Gestionar Equipos
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>
    <?php endif; ?>

    <?php if (isset($user['id_rol']) && $user['id_rol'] == 2): ?>
        <a href="<?= $appRoot ?>/incidencias" class="card dashboard-card" style="border-bottom-color: var(--color-peligro);">
            <div class="dashboard-card-header" style="background: rgba(239, 68, 68, 0.03);">
                <div class="dashboard-card-icon" style="background: white; color: var(--color-peligro);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <h3 class="dashboard-card-title">Incidencias</h3>
            </div>
            <div class="dashboard-card-body">
                <p class="dashboard-card-text">Reporte de fallas técnicas y seguimiento del estado de reparación de los recursos.</p>
                <div class="dashboard-card-footer">
                    <span class="btn btn-secundario" style="width: 100%; justify-content: space-between; border-color: rgba(239, 68, 68, 0.2);">
                        Gestionar Incidencias
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </span>
                </div>
            </div>
        </a>
    <?php else: ?>
        <a href="<?= $appRoot ?>/incidencias/create" class="card dashboard-card" style="border-bottom-color: var(--color-peligro);">
            <div class="dashboard-card-header" style="background: rgba(239, 68, 68, 0.03);">
                <div class="dashboard-card-icon" style="background: white; color: var(--color-peligro);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <h3 class="dashboard-card-title">Incidencias</h3>
            </div>
            <div class="dashboard-card-body">
                <p class="dashboard-card-text">Reporte fallas técnicas de los recursos o laboratorios.</p>
                <div class="dashboard-card-footer">
                    <span class="btn btn-secundario" style="width: 100%; justify-content: space-between; border-color: rgba(239, 68, 68, 0.2);">
                        Reportar Falla
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </span>
                </div>
            </div>
        </a>
    <?php endif; ?>

    <?php if (isset($user['id_rol']) && $user['id_rol'] == 2): ?>
    <a href="<?= $appRoot ?>/recursos" class="card dashboard-card" style="border-bottom-color: var(--color-primario);">
        <div class="dashboard-card-header" style="background: rgba(30, 41, 59, 0.03);">
            <div class="dashboard-card-icon" style="background: white; color: var(--color-primario);">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
            </div>
            <h3 class="dashboard-card-title">Recursos</h3>
        </div>
        <div class="dashboard-card-body">
            <p class="dashboard-card-text">Configuración de tipos de recursos, categorías y parámetros generales del sistema.</p>
            <div class="dashboard-card-footer">
                <span class="btn btn-secundario" style="width: 100%; justify-content: space-between; border-color: rgba(30, 41, 59, 0.2);">
                    Configurar
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </span>
            </div>
        </div>
    </a>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
