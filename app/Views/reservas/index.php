<?php
$title = "Reservas | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Reservas de Laboratorios</h1>
        <p class="pagina-subtitulo">Listado general de reservaciones y sus estados</p>
    </div>
    <div class="acciones">
        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas/create" class="btn btn-primario">Crear nueva reserva</a>
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
                <th>ID</th>
                <th>Laboratorio</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Motivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="reservas-body">
            <?php foreach ($reservas as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['id_reserva']) ?></td>
                <td><strong><?= htmlspecialchars($r['laboratorio_nombre'] ?? '') ?></strong></td>
                <td><?= htmlspecialchars($r['fecha_inicio']) ?></td>
                <td><?= htmlspecialchars($r['fecha_fin']) ?></td>
                <td><?= htmlspecialchars($r['usuario_nombre'] ?? '') ?></td>
                <td>
                    <?php 
                    $estado = strtolower($r['nombre_estado'] ?? '');
                    $badgeClass = 'badge-info';
                    if (strpos($estado, 'confirmada') !== false || strpos($estado, 'aprobada') !== false) $badgeClass = 'badge-success';
                    if (strpos($estado, 'cancelada') !== false || strpos($estado, 'rechazada') !== false) $badgeClass = 'badge-error';
                    if (strpos($estado, 'pendiente') !== false) $badgeClass = 'badge-warning';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($r['nombre_estado'] ?? 'No definido') ?></span>
                </td>
                <td><span title="<?= htmlspecialchars($r['motivo_uso'] ?? '') ?>"><?= htmlspecialchars(substr($r['motivo_uso'] ?? '', 0, 30)) ?><?= strlen($r['motivo_uso'] ?? '') > 30 ? '...' : '' ?></span></td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <?php if ($_SESSION['user']['id_rol'] == 2 || $r['id_usuario'] == ($_SESSION['user']['id'] ?? $_SESSION['user']['id_usuario'])): ?>
                            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas/edit/<?= $r['id_reserva'] ?>" class="btn btn-secundario btn-sm">Editar</a>
                            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas/delete/<?= $r['id_reserva'] ?>" class="btn btn-error btn-sm" onclick="return confirm('¿Eliminar reserva?')">Eliminar</a>
                        <?php else: ?>
                            <span class="badge badge-info" style="opacity: 0.6;">Solo lectura</span>
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
