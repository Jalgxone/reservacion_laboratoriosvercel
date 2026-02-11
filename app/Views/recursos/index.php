<?php
$title = "Recursos";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Gestión de Recursos</h1>
        <p class="pagina-subtitulo">Categorías y tipos de equipos disponibles</p>
    </div>
    <div class="acciones">
        <a href="<?= $appRoot ?>/recursos/create" class="btn btn-primario">Nueva Categoría</a>
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
                        <th>Nombre de la Categoría</th>
                        <th style="text-align: center;">Equipos</th>
                        <th>Observación</th>
                        <th style="text-align: center;">Mantenimiento</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="recursos-body">
                    <?php foreach ($recursos as $r): ?>
                        <tr>
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
                                    <span class="badge badge-advertencia">Sí</span>
                                <?php else: ?>
                                    <span class="badge badge-exito">No</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($r['esta_activo']): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-error">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="<?= $appRoot ?>/recursos/edit/<?= $r['id_categoria'] ?>" class="btn btn-secundario btn-sm" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <a href="<?= $appRoot ?>/recursos/toggleStatus/<?= $r['id_categoria'] ?>" class="btn btn-secundario btn-sm" title="<?= $r['esta_activo'] ? 'Desactivar' : 'Activar' ?>">
                                        <?php if ($r['esta_activo']): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        <?php endif; ?>
                                    </a>
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
