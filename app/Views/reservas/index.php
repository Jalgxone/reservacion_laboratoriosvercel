<?php
$title = "Mis Reservas";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Reservas de Laboratorios</h1>
        <p class="pagina-subtitulo">Listado general de reservaciones y sus estados</p>
    </div>
    <div class="acciones">
        <a href="<?= $appRoot ?>/reservas/create" class="btn btn-primario">Crear nueva reserva</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Historial de Reservas</h3>
    </div>
    
    <?php if (!empty($reservas)): ?>
    <table class="tabla" style="width: 100%;">
        <thead>
            <tr>
                <th>Laboratorio</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Motivo</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="reservas-body">
            <?php foreach ($reservas as $r): ?>
            <tr>
                <td><strong><?= htmlspecialchars($r['laboratorio_nombre'] ?? '') ?></strong></td>
                <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
                <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
                <td><?= htmlspecialchars($r['usuario_nombre'] ?? '') ?></td>
                <td>
                    <?php 
                    $estado = strtolower($r['nombre_estado'] ?? '');
                    $badgeClass = 'badge-info';
                    if (strpos($estado, 'confirmada') !== false || strpos($estado, 'aprobada') !== false) $badgeClass = 'badge-exito';
                    if (strpos($estado, 'cancelada') !== false || strpos($estado, 'rechazada') !== false) $badgeClass = 'badge-error';
                    if (strpos($estado, 'pendiente') !== false) $badgeClass = 'badge-advertencia';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($r['nombre_estado'] ?? 'No definido') ?></span>
                </td>
                <td><span title="<?= htmlspecialchars($r['motivo_uso'] ?? '') ?>"><?= htmlspecialchars(substr($r['motivo_uso'] ?? '', 0, 30)) ?><?= strlen($r['motivo_uso'] ?? '') > 30 ? '...' : '' ?></span></td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <?php if ($_SESSION['user']['id_rol'] == 2 || $r['id_usuario'] == ($_SESSION['user']['id'] ?? $_SESSION['user']['id_usuario'])): ?>
                            <a href="<?= $appRoot ?>/reservas/edit/<?= $r['id_reserva'] ?>" class="btn btn-secundario btn-sm" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <a href="<?= $appRoot ?>/reservas/delete/<?= $r['id_reserva'] ?>" class="btn btn-error btn-sm" data-confirm="¿Eliminar reserva?" title="Eliminar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding: var(--espacio-md); color: var(--color-texto-claro);">No hay reservas registradas.</div>
    <?php endif; ?>
</div>

<div id="paginacion-reservas" class="pagination-container"></div>

<?php require __DIR__ . '/../_footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => initPagination('reservas-body', 'paginacion-reservas', 5));
</script>
