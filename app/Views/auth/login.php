<?php
$title = "Iniciar Sesión | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 200px); padding: var(--espacio-lg);">
    <div class="card" style="width: 100%; max-width: 450px; border: none; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); overflow: hidden; padding: 0;">
        <div style="background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-primario-claro) 100%); padding: var(--espacio-xl) var(--espacio-lg); text-align: center; color: white;">
            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--espacio-md); border: 1px solid rgba(255,255,255,0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-acento);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 700; margin-bottom: var(--espacio-xs); color: white;">Bienvenido</h1>
            <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">Sistema de Reservación de Laboratorios</p>
        </div>
        
        <div style="padding: var(--espacio-xl) var(--espacio-lg); background-color: var(--color-superficie);">
            <?php if (!empty($errors ?? []) || !empty($_SESSION['error'])): ?>
                <div class="badge badge-error" style="width: 100%; margin-bottom: var(--espacio-lg); padding: var(--espacio-md); text-align: center; display: flex; align-items: center; justify-content: center; border-radius: var(--radio-borde); font-size: 0.85rem;">
                    <div style="width:100%; text-align:left;">
                    <?php if (!empty($errors ?? [])): ?>
                        <?php foreach ($errors as $field => $msgs): ?>
                            <?php foreach ($msgs as $m): ?>
                                <div><?= htmlspecialchars($m) ?></div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div><?= htmlspecialchars($_SESSION['error']) ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/login">
                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="email" class="form-label" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro);">Correo Electrónico</label>
                    <div style="position: relative;">
                        <input type="email" id="email" name="email" class="form-control" placeholder="usuario@institucion.edu" required autofocus style="padding-left: 3rem;" value="<?= htmlspecialchars($email ?? '') ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: var(--espacio-xl);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--espacio-xs);">
                        <label for="password" class="form-label" style="margin-bottom: 0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-texto-claro);">Contraseña</label>
                        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/forgotPassword" style="font-size: 0.75rem; color: var(--color-acento); text-decoration: none; font-weight: 500;">¿Olvidó su contraseña?</a>
                    </div>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required style="padding-left: 3rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro); pointer-events: none;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primario" style="width: 100%; padding: 0.875rem; font-size: 1rem; background: var(--color-primario); border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    Acceder al Sistema
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: var(--espacio-xs);"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>
        </div>
        
        <div style="padding: var(--espacio-md); text-align: center; border-top: 1px solid var(--color-borde); background-color: var(--color-fondo); font-size: 0.8rem; color: var(--color-texto-claro);">
            &copy; <?= date('Y') ?> Gestión de Laboratorios. Control de Acceso.
        </div>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
