<?php
$title = "Nueva Contraseña";
$fullWidth = true;
require __DIR__ . '/../_header.php';
?>

<div style="display: flex; flex-grow: 1; justify-content: center; align-items: center; padding: var(--espacio-xl) var(--espacio-lg); background-image: url('<?= $base ?>/auth_bg.png'); background-size: cover; background-position: center; background-attachment: fixed; position: relative;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.1); z-index: 1;"></div>
    <div class="card" style="width: 100%; max-width: 450px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden; padding: 0; border-radius: 1.5rem; z-index: 2; position: relative;">
        <div style="background: white; padding: var(--espacio-xl) var(--espacio-lg) var(--espacio-lg); text-align: center; border-bottom: 1px solid var(--color-borde);">
            <div style="width: 70px; height: 70px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--espacio-md); padding: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                <img src="<?= $base ?>/logo.png" alt="LabReserva Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; letter-spacing: -0.025em; margin: 0;">Nueva Contraseña</h1>
            <p style="color: var(--color-texto-claro); font-size: 0.9rem; margin-top: 4px;">Establece tu nueva contraseña de acceso</p>
        </div>
        
        <div style="padding: var(--espacio-xl) var(--espacio-lg); background-color: white;">
    
            <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/handleResetPassword" novalidate>
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="password" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Nueva Contraseña</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required autofocus style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-xl);">
                    <label for="confirm_password" class="form-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro); margin-bottom: 0.5rem; display: block;">Confirmar Nueva Contraseña</label>
                    <div style="position: relative;">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="••••••••" required style="padding-left: 3rem; height: 3.5rem; border-radius: 0.75rem; border: 1.5px solid var(--color-borde); transition: all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primario" style="width: 100%; padding: 1rem; font-size: 1.1rem; font-weight: 600; background: var(--color-primario); border: none; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.3); display: flex; align-items: center; justify-content: center; gap: 8px;">
                    Actualizar Contraseña
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </button>
            </form>
        </div>
        
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
