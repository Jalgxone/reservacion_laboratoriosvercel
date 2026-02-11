<?php
$title = "Crear Usuario";
$errors = $errors ?? [];
$old = $old ?? [];
$hideGlobalAlerts = true;
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Crear Usuario</h1>
        <p class="pagina-subtitulo">Complete los datos para registrar un nuevo usuario</p>
    </div>
    <div class="acciones">
        <a href="<?= $appRoot ?>/usuarios" class="btn btn-secundario">Volver</a>
    </div>
</div>

<div class="card">
    <form action="<?= $appRoot ?>/usuarios/store" method="post" novalidate>
        <div class="card-body" style="padding: var(--espacio-xl) var(--espacio-lg);">
                
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-lg); margin-bottom: var(--espacio-xl);">
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Nombre</label>
                    <div style="position: relative;">
                        <input type="text" name="nombre_completo" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($old['nombre_completo'] ?? '') ?>" required placeholder="Nombre">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <?php showFieldError('nombre_completo', $errors); ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Apellido</label>
                    <div style="position: relative;">
                        <input type="text" name="apellido" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($old['apellido'] ?? '') ?>" required placeholder="Apellido">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <?php showFieldError('apellido', $errors); ?>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                <label class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Correo Electrónico</label>
                <div style="position: relative;">
                    <input type="email" name="email" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required placeholder="correo@ejemplo.com">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <?php showFieldError('email', $errors); ?>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-lg); margin-bottom: var(--espacio-xl);">
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Cédula</label>
                    <div style="position: relative;">
                        <input type="text" name="cedula_identidad" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($old['cedula_identidad'] ?? ($old['cedula'] ?? '')) ?>" required placeholder="V-12345678">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><rect x="3" y="4" width="18" height="16" rx="2"></rect><line x1="7" y1="8" x2="17" y2="8"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="7" y1="16" x2="12" y2="16"></line></svg>
                    </div>
                    <?php showFieldError('cedula_identidad', $errors); ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Teléfono</label>
                    <div style="position: relative;">
                        <input type="text" name="telefono" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($old['telefono'] ?? '') ?>" required placeholder="+584121234567">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </div>
                    <?php showFieldError('telefono', $errors); ?>
                </div>
                </div>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-lg); margin-bottom: var(--espacio-xl);">
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Contraseña Inicial</label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" style="padding-left: 3rem;" required placeholder="••••••••">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <?php showFieldError('password', $errors); ?>
                </div>
                
                <div class="form-group">
                    <label class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Rol de Usuario</label>
                    <div style="position: relative;">
                        <select name="id_rol" class="form-control" style="padding-left: 3rem; appearance: none;">
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id_rol'] ?>" <?= (isset($old['id_rol']) && $old['id_rol'] == $r['id_rol']) ? 'selected' : '' ?>><?= htmlspecialchars($r['nombre_rol']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                </div>
            </div>

        </div>
        <div class="card-footer" style="padding: var(--espacio-lg); background: rgba(248, 250, 252, 0.5); display: flex; gap: 12px; justify-content: flex-end;">
            <a href="<?= $appRoot ?>/usuarios" class="btn btn-secundario" style="min-width: 120px; text-align: center;">Cancelar</a>
            <button type="submit" class="btn btn-primario" style="min-width: 160px;">Crear Usuario</button>
        </div>
        <div style="padding: 0 var(--espacio-lg) var(--espacio-lg); display: flex; justify-content: flex-end;">
            <?php 
            $inlineMsgs = getSystemMessages();
            if (!empty($inlineMsgs)): 
                foreach ($inlineMsgs as $m): ?>
                    <div class="alert-inline alert-inline-<?= $m['type'] ?>" style="margin-top: 0; min-width: 160px;">
                        <span></span>
                        <?= htmlspecialchars($m['content']) ?>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
