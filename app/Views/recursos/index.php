<?php
$title = "Recursos | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Gestión de Recursos</h1>
        <p class="pagina-subtitulo">Categorías y tipos de equipos disponibles</p>
    </div>
    <div class="acciones">
        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos/create" class="btn btn-primario">Nueva Categoría</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Listado de Categorías</h3>
    </div>
    
    <div class="tabla-contenedor">
        <?php if (!empty($recursos)): ?>
            <table class="tabla" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de la Categoría</th>
                        <th style="text-align: center;">Equipos</th>
                        <th>Observación</th>
                        <th style="text-align: center;">Mantenimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="recursos-body">
                    <?php foreach ($recursos as $r): ?>
                        <tr>
                            <td><small class="text-muted">#<?= htmlspecialchars($r['id_categoria']) ?></small></td>
                            <td><strong><?= htmlspecialchars($r['nombre_categoria']) ?></strong></td>
                            <td style="text-align: center;">
                                <span class="badge badge-info"><?= htmlspecialchars($r['cantidad']) ?></span>
                            </td>
                            <td>
                                <div style="max-width: 250px; font-size: 0.9rem; color: var(--color-texto-claro);" title="<?= htmlspecialchars($r['observacion']) ?>">
                                    <?= htmlspecialchars($r['observacion']) ?>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                <?php if (!empty($r['requiere_mantenimiento_mensual'])): ?>
                                    <span class="badge badge-error">Sí</span>
                                <?php else: ?>
                                    <span class="badge badge-success">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos/edit/<?= $r['id_categoria'] ?>" class="btn btn-secundario btn-sm">Editar</a>
                                    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos/delete/<?= $r['id_categoria'] ?>" onclick="return confirm('¿Eliminar esta categoría?')" class="btn btn-error btn-sm">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="padding: var(--espacio-lg); text-align: center; color: var(--color-texto-claro);">
                <p>No hay categorías registradas.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="paginacion-recursos" class="pagination-container"></div>

<?php require __DIR__ . '/../_footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => initPagination('recursos-body', 'paginacion-recursos', 5));
</script>
