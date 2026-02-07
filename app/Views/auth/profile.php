<?php
$title = "Mi Perfil | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 200px); padding: var(--espacio-lg);">
    <div class="card" style="width: 100%; max-width: 600px; border: none; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1); overflow: hidden; padding: 0;">
        <div style="background: linear-gradient(135deg, var(--color-primario) 0%, var(--color-primario-claro) 100%); padding: var(--espacio-xl) var(--espacio-lg); text-align: center; color: white;">
            <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--espacio-md); border: 2px solid rgba(255,255,255,0.2);">
                <span style="font-size: 2rem; font-weight: 700;"><?= strtoupper(substr($user['nombre_completo'], 0, 1)) ?></span>
            </div>
            <h1 style="font-size: 1.8rem; font-weight: 700; margin-bottom: var(--espacio-xs); color: white;"><?= htmlspecialchars($user['nombre_completo']) ?></h1>
            <div class="badge badge-acento" style="font-size: 0.8rem; padding: 0.25rem 0.75rem;">
                <?= $user['id_rol'] == 2 ? 'Administrador' : 'Cliente' ?>
            </div>
        </div>
        
        <div style="padding: var(--espacio-xl) var(--espacio-lg); background-color: var(--color-superficie);">
            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="badge badge-success" style="width: 100%; margin-bottom: var(--espacio-lg); padding: var(--espacio-md); text-align: center; border-radius: var(--radio-borde);">
                    <?= htmlspecialchars($_SESSION['flash']) ?>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
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

            <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/updateProfile">
                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" value="<?= htmlspecialchars($user['nombre_completo'] ?? '') ?>" required>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-lg);">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </div>
                
                <hr style="margin: var(--espacio-xl) 0; border: 0; border-top: 1px solid var(--color-borde);">
                
                <div style="margin-bottom: var(--espacio-lg);">
                    <h3 style="font-size: 1.1rem; font-weight: 600; margin-bottom: var(--espacio-sm);">Cambiar Contraseña</h3>
                    <p style="font-size: 0.85rem; color: var(--color-texto-claro); margin-bottom: var(--espacio-md);">Deja en blanco si no deseas cambiarla.</p>
                </div>

                <div class="form-group" style="margin-bottom: var(--espacio-xl);">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn btn-primario" style="width: 100%; padding: 0.875rem;">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
