<?php
$title = "Incidencias | Sistema de Reservación";
require __DIR__ . '/../_header.php';

$incidencias = $incidencias ?? [];

// Normalize rows logic (Preserved)
foreach ($incidencias as &$inc) {
    if (empty($inc['equipo_serial'])) {
        if (!empty($inc['codigo_serial'])) $inc['equipo_serial'] = $inc['codigo_serial'];
        elseif (!empty($inc['serial'])) $inc['equipo_serial'] = $inc['serial'];
        else $inc['equipo_serial'] = '';
    }
    if (empty($inc['usuario'])) {
        if (!empty($inc['nombre_completo'])) $inc['usuario'] = $inc['nombre_completo'];
        elseif (!empty($inc['usuario_nombre'])) $inc['usuario'] = $inc['usuario_nombre'];
        else $inc['usuario'] = '';
    }
    if (empty($inc['descripcion_problema']) && !empty($inc['descripcion'])) {
        $inc['descripcion_problema'] = $inc['descripcion'];
    }
    if (!empty($inc['fecha_reporte'])) {
        $ts = strtotime($inc['fecha_reporte']);
        if ($ts !== false) {
            $now = time();
            $diff = $now - $ts;
            if ($diff < 3600) $inc['when_human'] = 'Hace ' . intval($diff/60) . ' min';
            elseif ($diff < 86400) $inc['when_human'] = 'Hace ' . intval($diff/3600) . ' h';
            elseif ($diff < 172800) $inc['when_human'] = 'Ayer';
            else $inc['when_human'] = date('d/m/Y', $ts);
            $inc['fecha_reporte_fmt'] = date('d/m/Y H:i', $ts);
        } else {
            $inc['when_human'] = $inc['fecha_reporte'];
            $inc['fecha_reporte_fmt'] = $inc['fecha_reporte'];
        }
    } else {
        $inc['when_human'] = '-';
        $inc['fecha_reporte_fmt'] = '-';
    }

    $g = strtolower(trim((string)($inc['nivel_gravedad'] ?? '')));
    if ($g === 'alta') $inc['badge_class'] = 'badge-error';
    elseif ($g === 'media') $inc['badge_class'] = 'badge-warning';
    elseif ($g === 'baja') $inc['badge_class'] = 'badge-info';
    else $inc['badge_class'] = 'badge-warning';
}
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Reporte de Incidencias</h1>
        <p class="pagina-subtitulo">Listado completo de fallas registradas en los equipos</p>
    </div>
    <div class="acciones">
        <a class="btn btn-primario" href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=incidencias/create">Nueva Incidencia</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Historial de Incidencias</h3>
    </div>
    
    <div class="tabla-contenedor">
        <?php if (empty($incidencias)): ?>
            <div style="padding: var(--espacio-lg); text-align: center; color: var(--color-texto-claro);">
                <p>No hay incidencias registradas en el sistema.</p>
            </div>
        <?php else: ?>
            <table class="tabla" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Equipo</th>
                        <th>Reportado Por</th>
                        <th>Gravedad</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="incidencias-body">
                    <?php foreach ($incidencias as $inc): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($inc['fecha_reporte_fmt']) ?><br>
                                <small style="color: var(--color-texto-claro);"><?= htmlspecialchars($inc['when_human']) ?></small>
                            </td>
                            <td><strong><?= htmlspecialchars($inc['equipo_serial'] ?? 'N/A') ?></strong></td>
                            <td><?= htmlspecialchars($inc['usuario'] ?? 'Desconocido') ?></td>
                            <td><span class="badge <?= htmlspecialchars($inc['badge_class']) ?>"><?= htmlspecialchars(ucfirst($inc['nivel_gravedad'] ?? 'Media')) ?></span></td>
                            <td>
                                <?php if (!empty($inc['resuelto'])): ?>
                                    <span class="badge badge-success">Resuelto</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($inc['descripcion_problema'] ?? '') ?>">
                                    <?= htmlspecialchars($inc['descripcion_problema'] ?? '') ?>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=incidencias/edit/<?= urlencode($inc['id_incidencias'] ?? $inc['id_incidencia'] ?? '') ?>" class="btn btn-secundario btn-sm">Editar</a>
                                    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=incidencias/delete/<?= urlencode($inc['id_incidencias'] ?? $inc['id_incidencia'] ?? '') ?>" onclick="return confirm('¿Eliminar incidencia?')" class="btn btn-error btn-sm">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<div id="paginacion-incidencias" class="pagination-container"></div>

<?php require __DIR__ . '/../_footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => initPagination('incidencias-body', 'paginacion-incidencias', 5));
</script>
