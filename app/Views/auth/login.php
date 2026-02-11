<?php
$title = "Iniciar Sesión";
$fullWidth = true;
$errors = $errors ?? [];
$hideGlobalAlerts = true;
require __DIR__ . '/../_header.php';
?>

<div style="display: flex; flex-grow: 1; justify-content: center; align-items: center; padding: var(--espacio-xl) var(--espacio-lg); background-image: url('<?= $base ?>/auth_bg.png'); background-size: cover; background-position: center; background-attachment: fixed; position: relative;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.1); z-index: 1;"></div>
    <div class="card" style="width: 100%; max-width: 450px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden; padding: 0; border-radius: 1.5rem; z-index: 2; position: relative;">
        <div style="background: white; padding: var(--espacio-xl) var(--espacio-lg) var(--espacio-lg); text-align: center; border-bottom: 1px solid var(--color-borde);">
            <div style="width: 70px; height: 70px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--espacio-md); padding: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                <img src="<?= $base ?>/logo.png" alt="LabReserva Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; letter-spacing: -0.025em; margin: 0;">Iniciar Sesión</h1>
            <p style="color: var(--color-texto-claro); font-size: 0.9rem; margin-top: 4px;">Accede a tu cuenta de LabReserva</p>
        </div>
        
        <div style="padding: var(--espacio-xl) var(--espacio-lg); background-color: white;">
    
            <form method="post" action="<?= $appRoot ?>/auth/login" novalidate>
                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="email" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Correo Electrónico</label>
                    <div style="position: relative;">
                        <input type="email" id="email" name="email" class="form-control" placeholder="usuario@institucion.edu" required autofocus style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;" value="<?= htmlspecialchars($email ?? '') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                    <?php showFieldError('email', $errors); ?>
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
                    Acceder al Sistema
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
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

                <div style="text-align: center; margin-top: var(--espacio-lg);">
                    <a href="<?= $appRoot ?>/auth/forgotPassword" style="font-size: 0.9rem; color: var(--color-acento); text-decoration: none; font-weight: 600;">¿Olvidó su contraseña?</a>
                </div>
            </form>
        </div>

        <div style="padding: var(--espacio-lg); text-align: center; background-color: #f8fafc; border-top: 1px solid var(--color-borde);">
            <p style="margin-bottom: 0.5rem; font-size: 0.95rem; color: var(--color-texto-claro);">¿No tienes una cuenta?</p>
            <a href="<?= $appRoot ?>/auth/register" class="btn" style="color: var(--color-primario); font-weight: 700; text-decoration: none; font-size: 1rem; transition: color 0.2s;">Regístrate aquí</a>
        </div>
        
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
