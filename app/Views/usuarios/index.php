<?php
$title = "Usuarios";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Gestión de Usuarios</h1>
        <p class="pagina-subtitulo">Listado de usuarios registrados en el sistema</p>
    </div>
    <div class="acciones">
        <a href="<?= $appRoot ?>/usuarios/create" class="btn btn-primario">Crear usuario</a>
    </div>
</div>

<?php 
$admins = array_filter($usuarios, function($u) { return ($u['id_rol'] ?? 0) == 2; });
$clientes = array_filter($usuarios, function($u) { return ($u['id_rol'] ?? 1) == 1; });
?>



<div class="card">
    <div class="card-header" style="background: rgba(var(--rgb-acento, 245, 158, 11), 0.05); border-bottom: 1px solid var(--color-borde);">
        <h3 style="margin: 0; color: var(--color-acento); display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            Lista de Usuarios
        </h3>
    </div>

    <?php if (!empty($clientes)): ?>
    <div style="overflow-x: auto;">
        <table class="tabla" style="width:100%">
            <thead>
                <tr>
                    <th>Cédula</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th style="text-align: right;"></th>
                </tr>
            </thead>
            <tbody id="clientes-body">
                <?php foreach ($clientes as $u): ?>
                <tr>
                    <td style="font-family: monospace; font-weight: 600; color: var(--color-acento);"><?= htmlspecialchars($u['cedula_identidad'] ?? 'S/N') ?></td>
                    <td><div style="font-weight: 600;"><?= htmlspecialchars($u['nombre_completo'] . ' ' . ($u['apellido'] ?? '')) ?></div></td>
                    <td style="font-size: 0.9rem;"><?= htmlspecialchars($u['email']) ?></td>
                    <td style="font-size: 0.9rem;"><?= htmlspecialchars($u['telefono'] ?? '') ?></td>
                    <td>
                        <?php 
                        $estado = $u['estado'] ?? 'activo';
                        $badgeClass = 'badge-success';
                        if ($estado === 'pendiente') $badgeClass = 'badge-advertencia';
                        if ($estado === 'inactivo') $badgeClass = 'badge-error';
                        ?>
                        <span class="badge <?= $badgeClass ?>">
                            <?= ucfirst($estado) ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display:flex; gap:8px; justify-content: flex-end;">
                            <?php if ($estado === 'pendiente'): ?>
                                <a href="<?= $appRoot ?>/usuarios/approve/<?= $u['id_usuario'] ?>" class="btn btn-primario btn-sm" title="Aprobar Cuenta" style="background: var(--color-exito); border-color: var(--color-exito);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </a>
                            <?php else: ?>
                                <a href="<?= $appRoot ?>/usuarios/toggleStatus/<?= $u['id_usuario'] ?>" class="btn btn-secundario btn-sm" title="<?= ($estado === 'activo') ? 'Desactivar' : 'Activar' ?>">
                                    <?php if ($estado === 'activo'): ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                    <?php else: ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                            <a href="<?= $appRoot ?>/usuarios/edit/<?= $u['id_usuario'] ?>" class="btn btn-secundario btn-sm" title="Editar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>

                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div id="paginacion-clientes" class="pagination-container"></div>
    <?php else: ?>
    <div style="padding: var(--espacio-lg); text-align: center; color: var(--color-texto-claro);">No hay usuarios registrados.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('clientes-body')) {
            initPagination('clientes-body', 'paginacion-clientes', 8);
        }
    });
</script>
