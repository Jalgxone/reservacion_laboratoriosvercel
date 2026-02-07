<?php
$title = "Editar Laboratorio | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Editar Laboratorio</h1>
        <p class="pagina-subtitulo">Modificar información del espacio</p>
    </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3>Datos del Laboratorio</h3>
    </div>
    
    <div style="padding: var(--espacio-md);">
        <?php if (!empty($errors ?? [])): ?>
            <div class="badge badge-error" style="width: 100%; margin-bottom: var(--espacio-md); padding: var(--espacio-sm);">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php if (array_values($errors) === $errors): ?>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($errors as $field => $msgs): ?>
                            <?php foreach ((array)$msgs as $m): ?>
                                <li><?= htmlspecialchars($m) ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios/update/<?= (int)$lab['id_laboratorio'] ?>">
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del Laboratorio</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($lab['nombre'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="ubicacion" class="form-label">Ubicación / Edificio</label>
                <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="<?= htmlspecialchars($lab['ubicacion'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="capacidad" class="form-label">Capacidad Máxima (personas)</label>
                <input type="number" id="capacidad" name="capacidad" class="form-control" value="<?= htmlspecialchars($lab['capacidad'] ?? $lab['capacidad_personas'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label class="form-label" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="esta_activo" name="esta_activo" value="1" <?= (!empty($lab['esta_activo'])) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                    Laboratorio Activo y Disponible
                </label>
            </div>
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md);">
                <button type="submit" class="btn btn-primario">Guardar Cambios</button>
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
