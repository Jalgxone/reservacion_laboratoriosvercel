<?php
$errors = $errors ?? [];
$old = $old ?? [];
$hideGlobalAlerts = true;
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Agregar Equipo</h1>
        <p class="pagina-subtitulo">Registrar nuevo ítem en el inventario</p>
    </div>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3>Datos del Equipo</h3>
    </div>
    
    <div style="padding: var(--espacio-md);">

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios/store" novalidate>
            <div class="form-group">
                <label class="form-label">ID de Inventario</label>
                <input type="text" class="form-control" value="Se generará automáticamente" disabled style="background-color: var(--color-fondo-claro); font-style: italic;">
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-md);">
                <div class="form-group">
                    <label for="id_laboratorio" class="form-label">Laboratorio Destino</label>
                    <select name="id_laboratorio" id="id_laboratorio" class="form-control">
                        <?php foreach ($labs as $l): ?>
                            <option value="<?= $l['id_laboratorio'] ?>" <?= (!empty($old['id_laboratorio']) && $old['id_laboratorio'] == $l['id_laboratorio']) ? 'selected' : '' ?>><?= htmlspecialchars($l['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php showFieldError('id_laboratorio', $errors); ?>
                </div>
                
                <div class="form-group">
                    <label for="id_categoria" class="form-label">Categoría</label>
                    <select name="id_categoria" id="id_categoria" class="form-control">
                        <?php foreach ($cats as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>" <?= (!empty($old['id_categoria']) && $old['id_categoria'] == $c['id_categoria']) ? 'selected' : '' ?>><?= htmlspecialchars($c['nombre_categoria']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php showFieldError('id_categoria', $errors); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="marca_modelo" class="form-label">Marca y Modelo</label>
                <input type="text" id="marca_modelo" name="marca_modelo" class="form-control" value="<?= htmlspecialchars($old['marca_modelo'] ?? '') ?>" placeholder="Ej: Dell Precision 3660">
                <small style="color: var(--color-texto-suave); display: block; margin-top: 4px;">Formato requerido: Marca Modelo (Ej: Dell Latitude, HP ProBook)</small>
                <?php showFieldError('marca_modelo', $errors); ?>
            </div>

            <div class="form-group">
                <label for="estado_operativo" class="form-label">Estado Inicial</label>
                <select name="estado_operativo" id="estado_operativo" class="form-control">
                    <option <?= (isset($old['estado_operativo']) ? ($old['estado_operativo'] == 'Operativo') : true) ? 'selected' : '' ?>>Operativo</option>
                    <option <?= (isset($old['estado_operativo']) && $old['estado_operativo'] == 'En Reparación') ? 'selected' : '' ?>>En Reparación</option>
                    <option <?= (isset($old['estado_operativo']) && $old['estado_operativo'] == 'Baja') ? 'selected' : '' ?>>Baja</option>
                </select>
            </div>
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md); flex-wrap: wrap; justify-content: flex-end;">
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios" class="btn btn-secundario" style="min-width: 120px; text-align: center;">Cancelar</a>
                <button type="submit" class="btn btn-primario" style="min-width: 160px;">Registrar Equipo</button>

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
