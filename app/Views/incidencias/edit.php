<?php
$title = "Editar Incidencia | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Editar Incidencia</h1>
        <p class="pagina-subtitulo">Modificar reporte #<?= htmlspecialchars($incidencia['id_incidencia']) ?></p>
    </div>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3>Actualizar Estado del Reporte</h3>
    </div>
    
    <div style="padding: var(--espacio-md);">
        <?php if (!empty($errors)): ?>
            <div class="badge badge-error" style="width: 100%; margin-bottom: var(--espacio-md); padding: var(--espacio-sm);">
                <?php foreach ($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=incidencias/update/<?= $incidencia['id_incidencia'] ?>">
            <div class="form-group">
                <label for="id_equipo" class="form-label">Equipo</label>
                <select id="id_equipo" name="id_equipo" class="form-control">
                    <?php foreach ($equipos as $eq): ?>
                        <option value="<?= $eq['id_equipo'] ?>" <?= ($incidencia['id_equipo'] == $eq['id_equipo']) ? 'selected' : '' ?>><?= htmlspecialchars($eq['codigo_serial']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Gravedad</label>
                <div style="display: flex; gap: var(--espacio-lg); margin-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="gravedad" value="baja" <?= ($incidencia['nivel_gravedad'] ?? '') == 'baja' ? 'checked' : '' ?>> Baja
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="gravedad" value="media" <?= ($incidencia['nivel_gravedad'] ?? '') == 'media' ? 'checked' : '' ?>> Media
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="gravedad" value="alta" <?= ($incidencia['nivel_gravedad'] ?? '') == 'alta' ? 'checked' : '' ?>> Alta
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion_problema" class="form-label">Descripción Técnica</label>
                <textarea id="descripcion_problema" name="descripcion_problema" class="form-control" rows="4"><?= htmlspecialchars($incidencia['descripcion_problema']) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="resuelto" <?= $incidencia['resuelto'] ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                    Marcar como Resuelto
                </label>
            </div>
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md);">
                <button type="submit" class="btn btn-primario">Guardar Cambios</button>
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=incidencias" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
