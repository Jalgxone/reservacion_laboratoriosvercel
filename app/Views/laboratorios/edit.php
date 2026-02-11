<?php
$errors = $errors ?? [];
$hideGlobalAlerts = true;
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

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios/update/<?= (int)$lab['id_laboratorio'] ?>" novalidate>
            <div class="form-group">
                <label for="nombre" class="form-label">Nombre del Laboratorio</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($lab['nombre'] ?? '') ?>" required placeholder="Ej. Computación #1, Sala A">
                <?php showFieldError('nombre', $errors); ?>
                <small style="color: var(--color-texto-claro); font-size: 0.8rem;">Solo letras, números, espacios, comas y #.</small>
            </div>
            
            <div class="form-group">
                <label for="ubicacion" class="form-label">Ubicación / Edificio</label>
                <input type="text" id="ubicacion" name="ubicacion" class="form-control" value="<?= htmlspecialchars($lab['ubicacion'] ?? '') ?>" placeholder="Ej. Edificio #2, Planta Baja">
                <?php showFieldError('ubicacion', $errors); ?>
                <small style="color: var(--color-texto-claro); font-size: 0.8rem;">Solo letras, números, espacios, comas y #.</small>
            </div>
            
            <div class="form-group">
                <label for="capacidad_personas" class="form-label">Capacidad Máxima (personas)</label>
                <input type="number" id="capacidad_personas" name="capacidad_personas" class="form-control" value="<?= htmlspecialchars($lab['capacidad_personas'] ?? '') ?>" placeholder="Valor entre 10 y 50" min="10" max="50">
                <?php showFieldError('capacidad_personas', $errors); ?>
                <small style="color: var(--color-texto-claro); font-size: 0.8rem;">Mínimo 10, Máximo 50 personas.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="esta_activo" name="esta_activo" value="1" <?= (!empty($lab['esta_activo'])) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                    Laboratorio Activo y Disponible
                </label>
            </div>
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md); flex-wrap: wrap; justify-content: flex-end;">
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios" class="btn btn-secundario" style="min-width: 120px; text-align: center;">Cancelar</a>
                <button type="submit" class="btn btn-primario" style="min-width: 160px;">Guardar Cambios</button>

                <?php 
                $inlineMsgs = getSystemMessages();
                if (!empty($inlineMsgs)): 
                    foreach ($inlineMsgs as $m): ?>
                        <div class="alert-inline alert-inline-<?= $m['type'] ?>" style="width: 100%; margin-top: 1rem;">
                            <span></span>
                            <?= htmlspecialchars($m['content']) ?>
                        </div>
                    <?php endforeach;
                endif; ?>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
