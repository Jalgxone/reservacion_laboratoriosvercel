<?php
$title = "Agregar Equipo | Sistema de Reservación";
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
        <?php if (!empty($errors)): ?>
            <div class="badge badge-error" style="width: 100%; margin-bottom: var(--espacio-md); padding: var(--espacio-sm);">
                <?php
                if (array_values($errors) === $errors) {
                    foreach ($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>';
                } else {
                    foreach ($errors as $field => $msgs) {
                        foreach ((array)$msgs as $m) echo '<div>' . htmlspecialchars($m) . '</div>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios/store">
            <div class="form-group">
                <label for="codigo_serial" class="form-label">Número de Serie / Código</label>
                <input type="text" id="codigo_serial" name="codigo_serial" class="form-control" value="<?= htmlspecialchars($old['codigo_serial'] ?? '') ?>" placeholder="Ej. SN-12345678">
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-md);">
                <div class="form-group">
                    <label for="id_laboratorio" class="form-label">Laboratorio Destino</label>
                    <select name="id_laboratorio" id="id_laboratorio" class="form-control">
                        <?php foreach ($labs as $l): ?>
                            <option value="<?= $l['id_laboratorio'] ?>" <?= (!empty($old['id_laboratorio']) && $old['id_laboratorio'] == $l['id_laboratorio']) ? 'selected' : '' ?>><?= htmlspecialchars($l['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="id_categoria" class="form-label">Categoría</label>
                    <select name="id_categoria" id="id_categoria" class="form-control">
                        <?php foreach ($cats as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>" <?= (!empty($old['id_categoria']) && $old['id_categoria'] == $c['id_categoria']) ? 'selected' : '' ?>><?= htmlspecialchars($c['nombre_categoria']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="marca_modelo" class="form-label">Marca y Modelo</label>
                <input type="text" id="marca_modelo" name="marca_modelo" class="form-control" value="<?= htmlspecialchars($old['marca_modelo'] ?? '') ?>" placeholder="Ej. Dell OptiPlex 7080">
            </div>

            <div class="form-group">
                <label for="estado_operativo" class="form-label">Estado Inicial</label>
                <select name="estado_operativo" id="estado_operativo" class="form-control">
                    <option <?= (isset($old['estado_operativo']) ? ($old['estado_operativo'] == 'Operativo') : true) ? 'selected' : '' ?>>Operativo</option>
                    <option <?= (isset($old['estado_operativo']) && $old['estado_operativo'] == 'En Reparación') ? 'selected' : '' ?>>En Reparación</option>
                    <option <?= (isset($old['estado_operativo']) && $old['estado_operativo'] == 'Baja') ? 'selected' : '' ?>>Baja</option>
                </select>
            </div>
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md);">
                <button type="submit" class="btn btn-primario">Registrar Equipo</button>
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
