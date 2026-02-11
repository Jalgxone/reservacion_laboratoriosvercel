<?php
$title = "Laboratorios";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Lista de Laboratorios</h1>
        <p class="pagina-subtitulo">Gestión de espacios físicos y laboratorios</p>
    </div>
    <?php if ($_SESSION['user']['id_rol'] == 2): ?>
    <div class="acciones">
        <a href="<?= $appRoot ?>/laboratorios/create" class="btn btn-primario">Crear laboratorio</a>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Laboratorios Registrados</h3>
    </div>
    
    <?php if (!empty($labs)): ?>
    <table class="tabla" style="width: 100%;">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Capacidad</th>
                <th>Activo</th>
                <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                <th></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($labs as $lab): ?>
            <tr>
                <td><strong><?= htmlspecialchars($lab['nombre']) ?></strong></td>
                <td><?= htmlspecialchars($lab['ubicacion']) ?></td>
                <td><?= htmlspecialchars($lab['capacidad_personas']) ?> personas</td>
                <td>
                    <?php if ($lab['esta_activo']): ?>
                        <span class="badge badge-success">Activo</span>
                    <?php else: ?>
                        <span class="badge badge-error">Inactivo</span>
                    <?php endif; ?>
                </td>
                <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?= $appRoot ?>/laboratorios/edit/<?= (int)$lab['id_laboratorio'] ?>" class="btn btn-secundario btn-sm" title="Editar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <a href="<?= $appRoot ?>/laboratorios/toggleStatus/<?= (int)$lab['id_laboratorio'] ?>" class="btn btn-secundario btn-sm" title="<?= $lab['esta_activo'] ? 'Desactivar' : 'Activar' ?>">
                            <?php if ($lab['esta_activo']): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                            <?php endif; ?>
                        </a>
                    </div>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding: var(--espacio-md); color: var(--color-texto-claro);">No hay laboratorios registrados.</div>
    <?php endif; ?>
</div>



<?php require __DIR__ . '/../_footer.php'; ?>
