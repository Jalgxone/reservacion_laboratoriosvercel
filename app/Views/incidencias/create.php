<?php
$title = "Nuevo Reporte";
$errors = $errors ?? [];
$old = $old ?? [];
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Nuevo Reporte de Incidencia</h1>
        <p class="pagina-subtitulo">Complete los detalles de la falla técnica</p>
    </div>
</div>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <h3>Detalles del Reporte</h3>
    </div>
    
    <div style="padding: var(--espacio-md);">

        <form method="post" action="<?= $appRoot ?>/incidencias/store" novalidate>
            <div class="form-group">
                <label for="id_equipo" class="form-label">Equipo Afectado</label>
                <select id="id_equipo" name="id_equipo" class="form-control" required>
                    <option value="">Seleccione el equipo...</option>
                    <?php foreach ($equipos as $eq): ?>
                        <option value="<?= $eq['id_equipo'] ?>" <?= (isset($old['id_equipo']) && $old['id_equipo'] == $eq['id_equipo']) ? 'selected' : '' ?>><?= htmlspecialchars(($eq['codigo_serial'] ?? 'N/A') . ' - ' . ($eq['marca_modelo'] ?? '')) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php showFieldError('id_equipo', $errors); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Nivel de Gravedad</label>
                <div style="display: flex; gap: var(--espacio-lg); margin-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="gravedad" value="baja" <?= (isset($old['nivel_gravedad']) && $old['nivel_gravedad'] === 'baja') ? 'checked' : '' ?>> Baja
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="gravedad" value="media" <?= (!isset($old['nivel_gravedad']) || $old['nivel_gravedad'] === 'media') ? 'checked' : '' ?>> Media
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="gravedad" value="alta" <?= (isset($old['nivel_gravedad']) && $old['nivel_gravedad'] === 'alta') ? 'checked' : '' ?>> Alta
                    </label>
                </div>
                <?php showFieldError('nivel_gravedad', $errors); ?>
            </div>

            <div class="form-group">
                <label for="descripcion_problema" class="form-label">Descripción del Problema</label>
                <textarea id="descripcion_problema" name="descripcion_problema" class="form-control" rows="6" placeholder="Describa detalladamente qué sucedió..."><?= htmlspecialchars($old['descripcion_problema'] ?? '') ?></textarea>
                <?php showFieldError('descripcion_problema', $errors); ?>
            </div>

            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md);">
                <button type="submit" class="btn btn-primario">Enviar Reporte</button>
                <a href="<?= $appRoot ?>/incidencias" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
