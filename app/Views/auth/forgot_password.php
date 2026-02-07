<?php
$title = "Recuperar Contraseña | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 200px); padding: var(--espacio-lg);">
    <div class="card" style="width: 100%; max-width: 450px; border: none; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); overflow: hidden; padding: 0;">
        <div style="background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-primario-claro) 100%); padding: var(--espacio-xl) var(--espacio-lg); text-align: center; color: white;">
            <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--espacio-md); border: 1px solid rgba(255,255,255,0.2);">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-acento);"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 700; margin-bottom: var(--espacio-xs); color: white;">¿Olvidó su contraseña?</h1>
            <p style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">Te enviaremos un link de recuperación</p>
        </div>
        
        <div style="padding: var(--espacio-xl) var(--espacio-lg); background-color: var(--color-superficie);">
            <?php if (!empty($errors ?? []) || !empty($_SESSION['error'])): ?>
                <div class="badge badge-error" style="width: 100%; margin-bottom: var(--espacio-lg); padding: var(--espacio-md); text-align: center; border-radius: var(--radio-borde);">
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

            <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/sendResetLink">
                <div class="form-group" style="margin-bottom: var(--espacio-xl);">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="usuario@institucion.edu" required autofocus value="<?= htmlspecialchars($email ?? '') ?>">
                </div>
                
                <button type="submit" class="btn btn-primario" style="width: 100%; padding: 0.875rem;">
                    Enviar Link de Recuperación
                </button>
            </form>

            <div style="margin-top: var(--espacio-lg); text-align: center; font-size: 0.9rem;">
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth" style="color: var(--color-acento); font-weight: 600; text-decoration: none;">Volver al Inicio de Sesión</a>
            </div>
        </div>
        
        <div style="padding: var(--espacio-md); text-align: center; border-top: 1px solid var(--color-borde); background-color: var(--color-fondo); font-size: 0.8rem; color: var(--color-texto-claro);">
            &copy; <?= date('Y') ?> Gestión de Laboratorios.
        </div>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
