<?php
$title = "Mi Perfil";
$errors = $errors ?? [];
$hideGlobalAlerts = true;
require __DIR__ . '/../_header.php';
?>

<div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 200px); padding: var(--espacio-lg);">
    <div class="card" style="width: 100%; max-width: 600px; border: none; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); overflow: hidden; padding: 0;">
        <div style="background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-primario-claro) 100%); padding: var(--espacio-xl) var(--espacio-lg); text-align: center; color: white;">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--espacio-md); border: 2px solid rgba(255,255,255,0.2);">
                <span style="font-size: 2rem; font-weight: 700;"><?= strtoupper(substr($user['nombre_completo'], 0, 1)) ?></span>
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 700; margin-bottom: var(--espacio-xs); color: white;"><?= htmlspecialchars($user['nombre_completo'] . ' ' . $user['apellido']) ?></h1>
            <div style="display: flex; justify-content: center; gap: 8px; align-items: center;">
                <div class="badge badge-acento" style="font-size: 0.8rem; padding: 0.25rem 0.75rem;">
                    <?= $user['id_rol'] == 2 ? 'Administrador' : 'Cliente' ?>
                </div>
                <span style="font-size: 0.85rem; opacity: 0.9;">Miembro desde: <?= date('d/m/Y', strtotime($user['fecha_registro'])) ?></span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: var(--color-borde); border-bottom: 1px solid var(--color-borde);">
            <div style="background: white; padding: var(--espacio-md); text-align: center;">
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--color-primario);"><?= $stats['total'] ?></div>
                <div style="font-size: 0.75rem; color: var(--color-texto-claro); text-transform: uppercase;">Total Reservas</div>
            </div>
            <div style="background: white; padding: var(--espacio-md); text-align: center;">
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--color-exito);"><?= $stats['confirmadas'] ?></div>
                <div style="font-size: 0.75rem; color: var(--color-texto-claro); text-transform: uppercase;">Confirmadas</div>
            </div>
            <div style="background: white; padding: var(--espacio-md); text-align: center;">
                <div style="font-size: 1.25rem; font-weight: 700; color: var(--color-advertencia);"><?= $stats['pendientes'] ?></div>
                <div style="font-size: 0.75rem; color: var(--color-texto-claro); text-transform: uppercase;">Pendientes</div>
            </div>
        </div>
        
        <div style="padding: var(--espacio-xl) var(--espacio-lg); background-color: var(--color-superficie);">
    
            <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/updateProfile" novalidate>
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-lg); margin-bottom: var(--espacio-xl);">
                    <div class="form-group">
                        <label for="nombre_completo" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Nombre</label>
                        <div style="position: relative;">
                            <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($user['nombre_completo'] ?? '') ?>" required>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <?php showFieldError('nombre_completo', $errors); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellido" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Apellido</label>
                        <div style="position: relative;">
                            <input type="text" id="apellido" name="apellido" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($user['apellido'] ?? '') ?>" required>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <?php showFieldError('apellido', $errors); ?>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="email" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Correo Electrónico</label>
                    <div style="position: relative;">
                        <input type="email" id="email" name="email" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <?php showFieldError('email', $errors); ?>
                </div>

                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-lg); margin-bottom: var(--espacio-xl);">
                    <div class="form-group">
                        <label for="cedula_identidad" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Cédula</label>
                        <div style="position: relative;">
                            <input type="text" id="cedula_identidad" name="cedula_identidad" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($user['cedula_identidad'] ?? '') ?>" placeholder="V-12345678" required>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><rect x="3" y="4" width="18" height="16" rx="2"></rect><line x1="7" y1="8" x2="17" y2="8"></line><line x1="7" y1="12" x2="17" y2="12"></line><line x1="7" y1="16" x2="12" y2="16"></line></svg>
                        </div>
                        <?php showFieldError('cedula_identidad', $errors); ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase;">Teléfono</label>
                        <div style="position: relative;">
                            <input type="text" id="telefono" name="telefono" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($user['telefono'] ?? '') ?>" placeholder="+584121234567" required>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <?php showFieldError('telefono', $errors); ?>
                    </div>
                </div>

                <div style="background: rgba(241, 245, 249, 0.5); padding: var(--espacio-lg); border-radius: 1rem; border: 1px dashed var(--color-borde); margin-bottom: var(--espacio-xl);">
                    <div style="margin-bottom: var(--espacio-md);">
                        <h3 style="font-size: 1rem; font-weight: 700; color: var(--color-primario); display: flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Seguridad y Contraseña
                        </h3>
                        <p style="font-size: 0.85rem; color: var(--color-texto-claro);">Completa estos campos solo si deseas cambiar tu contraseña.</p>
                    </div>

                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-lg);">
                        <div class="form-group">
                            <label for="new_password" class="form-label" style="font-size: 0.75rem; font-weight: 700;">Nueva Contraseña</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="••••••••">
                            <?php showFieldError('password', $errors); ?>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password" class="form-label" style="font-size: 0.75rem; font-weight: 700;">Confirmar</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="••••••••">
                            <?php showFieldError('confirm_password', $errors); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-xl); background: rgba(14, 165, 233, 0.05); padding: var(--espacio-lg); border-radius: 1rem; border: 1px solid rgba(14, 165, 233, 0.2);">
                    <label for="current_password" class="form-label" style="font-size: 0.75rem; font-weight: 800; color: var(--color-acento);">Contraseña Actual (Requerido para guardar)</label>
                    <div style="position: relative;">
                        <input type="password" id="current_password" name="current_password" class="form-control" style="padding-left: 3rem; border-color: var(--color-acento);" placeholder="Ingresa tu contraseña actual" required>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-acento);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <?php showFieldError('current_password', $errors); ?>
                </div>
                
                <button type="submit" class="btn btn-primario" style="width: 100%; padding: 1rem; font-weight: 700; border-radius: 0.75rem; box-shadow: var(--sombra-md);">
                    Actualizar Información de Perfil
                </button>

                <?php 
                $inlineMsgs = getSystemMessages();
                if (!empty($inlineMsgs)): 
                    foreach ($inlineMsgs as $m): ?>
                        <div class="alert-inline alert-inline-<?= $m['type'] ?>">
                            <span></span>
                            <?= htmlspecialchars($m['content']) ?>
                        </div>
                    <?php endforeach;
                endif; ?>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
