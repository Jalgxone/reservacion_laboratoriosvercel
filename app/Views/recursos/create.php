<?php
$errors = $errors ?? [];
$old = $old ?? [];
$hideGlobalAlerts = true;
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Nueva Categoría</h1>
        <p class="pagina-subtitulo">Agregar una nueva clasificación de equipos</p>
    </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3>Datos de la Categoría</h3>
    </div>
    
    <div style="padding: var(--espacio-md);">

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos/store" novalidate>
            <div class="form-group">
                <label for="nombre_categoria" class="form-label">Nombre de la Categoría</label>
                <select id="nombre_categoria" name="nombre_categoria" class="form-control" required>
                    <option value="" disabled <?= empty($old['nombre_categoria']) ? 'selected' : '' ?>>Seleccione una categoría...</option>
                    <?php if (isset($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= ($old['nombre_categoria'] ?? '') === $cat ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?php showFieldError('nombre_categoria', $errors); ?>
            </div>

            <div class="form-group">
                <label for="observacion" class="form-label">Observaciones</label>
                <textarea id="observacion" name="observacion" class="form-control" rows="4" placeholder="Detalles adicionales sobre esta categoría..."><?= htmlspecialchars($old['observacion'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="requiere_mantenimiento_mensual" <?= (!empty($old['requiere_mantenimiento_mensual'])) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                    Requiere Mantenimiento Mensual
                </label>
            </div>
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md); flex-wrap: wrap; justify-content: flex-end;">
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos" class="btn btn-secundario" style="min-width: 120px; text-align: center;">Cancelar</a>
                <button type="submit" class="btn btn-primario" style="min-width: 160px;">Agregar Categoría</button>

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
