<?php
$title = "Nueva Categoría | Sistema de Reservación";
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
        <?php if (!empty($errors)): ?>
            <div class="badge badge-error" style="width: 100%; margin-bottom: var(--espacio-md); padding: var(--espacio-sm);">
                <?php foreach ($errors as $e) echo '<div>' . htmlspecialchars($e) . '</div>'; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos/store">
            <div class="form-group">
                <label for="nombre_categoria" class="form-label">Nombre de la Categoría</label>
                <input type="text" id="nombre_categoria" name="nombre_categoria" class="form-control" value="<?= htmlspecialchars($old['nombre_categoria'] ?? '') ?>" placeholder="Ej. Laptops, Proyectores..." required>
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
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md);">
                <button type="submit" class="btn btn-primario">Agregar Categoría</button>
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
