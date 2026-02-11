<?php
$title = "Registrarse";
$fullWidth = true;
$errors = $errors ?? [];
$hideGlobalAlerts = true;
require __DIR__ . '/../_header.php';
?>

<div style="display: flex; flex-grow: 1; justify-content: center; align-items: center; padding: var(--espacio-xl) var(--espacio-lg); background-image: url('<?= $base ?>/auth_bg.png'); background-size: cover; background-position: center; background-attachment: fixed; position: relative;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.1); z-index: 1;"></div>
    <div class="card" style="width: 100%; max-width: 500px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden; padding: 0; border-radius: 1.5rem; z-index: 2; position: relative;">
        <div style="background: white; padding: var(--espacio-xl) var(--espacio-lg) var(--espacio-lg); text-align: center; border-bottom: 1px solid var(--color-borde);">
            <div style="width: 70px; height: 70px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--espacio-md); padding: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                <img src="<?= $base ?>/logo.png" alt="LabReserva Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; letter-spacing: -0.025em; margin: 0;">Crear Cuenta</h1>
            <p style="color: var(--color-texto-claro); font-size: 0.9rem; margin-top: 4px;">Únete a la plataforma de gestión</p>
        </div>
        
        <div style="padding: var(--espacio-xl) var(--espacio-lg); background-color: white;">
    
            <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/store" novalidate>
                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="nombre_completo" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Nombre</label>
                    <div style="position: relative;">
                        <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" placeholder="Juan" required autofocus style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;" value="<?= htmlspecialchars($nombre_completo ?? '') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <?php showFieldError('nombre_completo', $errors); ?>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="apellido" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Apellido</label>
                    <div style="position: relative;">
                        <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Gómez" required style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;" value="<?= htmlspecialchars($apellido ?? '') ?>">
                    </div>
                    <?php showFieldError('apellido', $errors); ?>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="email" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Correo Electrónico</label>
                    <div style="position: relative;">
                        <input type="email" id="email" name="email" class="form-control" placeholder="ejemplo@gmail.com" required style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;" value="<?= htmlspecialchars($email ?? '') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <?php showFieldError('email', $errors); ?>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="cedula" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Cédula de Identidad</label>
                    <div style="position: relative;">
                        <input type="text" id="cedula" name="cedula" class="form-control" placeholder="V-12345678" required style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;" value="<?= htmlspecialchars($cedula ?? '') ?>">
                    </div>
                    <?php showFieldError('cedula', $errors); ?>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="telefono" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Número de Teléfono</label>
                    <div style="position: relative;">
                        <input type="text" id="telefono" name="telefono" class="form-control" placeholder="+584121234567" required style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;" value="<?= htmlspecialchars($telefono ?? '') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </div>
                    <?php showFieldError('telefono', $errors); ?>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="password" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Contraseña</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <?php showFieldError('password', $errors); ?>
                </div>

                
                
                <button type="submit" class="btn btn-primario" style="width: 100%; padding: 1rem; font-size: 1.1rem; font-weight: 600; background: var(--color-primario); border: none; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.3); display: flex; align-items: center; justify-content: center; gap: 8px; transition: transform 0.2s;">
                    Registrarse en LabReserva
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
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

        <div style="padding: var(--espacio-lg); text-align: center; background-color: #f8fafc; border-top: 1px solid var(--color-borde);">
            <p style="margin-bottom: 0.5rem; font-size: 0.95rem; color: var(--color-texto-claro);">¿Ya tienes una cuenta?</p>
            <a href="<?= $appRoot ?>/auth" style="color: var(--color-primario); font-weight: 700; text-decoration: none; font-size: 1rem; transition: color 0.2s;">Inicia sesión aquí</a>
        </div>
        
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
