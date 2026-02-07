<?php
$title = "Editar Reserva | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Editar Reserva #<?= htmlspecialchars($reserva['id_reserva']) ?></h1>
        <p class="pagina-subtitulo">Modificar detalles de la reservación</p>
    </div>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <h3>Información de la Reserva</h3>
    </div>
    
    <div style="padding: var(--espacio-md);">
        <?php if (!empty($errors)): ?>
            <div class="badge badge-error" style="width: 100%; margin-bottom: var(--espacio-md); padding: var(--espacio-sm);">
                <?php
                if (array_values($errors) === $errors) {
                    foreach ($errors as $err) echo '<div>' . htmlspecialchars($err) . '</div>';
                } else {
                    foreach ($errors as $field => $msgs) {
                        foreach ((array)$msgs as $m) echo '<div>' . htmlspecialchars($m) . '</div>';
                    }
                }
                ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas/update/<?= $reserva['id_reserva'] ?>">
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-md);">
                <div class="form-group">
                    <label for="id_laboratorio" class="form-label">Laboratorio</label>
                    <select name="id_laboratorio" id="id_laboratorio" class="form-control">
                            <?php foreach ($labs as $lab): ?>
                                <option value="<?= $lab['id_laboratorio'] ?>" <?= (isset($old['id_laboratorio']) ? ($old['id_laboratorio'] == $lab['id_laboratorio']) : ($reserva['id_laboratorio'] == $lab['id_laboratorio'])) ? 'selected' : '' ?>><?= htmlspecialchars($lab['nombre']) ?></option>
                            <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="id_estado" class="form-label">Estado</label>
                    <select name="id_estado" id="id_estado" class="form-control">
                        <?php if (!empty($estados)): ?>
                            <?php foreach ($estados as $es): ?>
                                <option value="<?= $es['id_estado'] ?>" <?= (isset($old['id_estado']) ? ($old['id_estado'] == $es['id_estado']) : ($reserva['id_estado'] == $es['id_estado'])) ? 'selected' : '' ?>><?= htmlspecialchars($es['nombre_estado']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">(Sin estados disponibles)</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-md);">
                <div class="form-group">
                    <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
                    <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?= isset($old['fecha_inicio']) ? str_replace(' ', 'T', $old['fecha_inicio']) : str_replace(' ', 'T', $reserva['fecha_inicio']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="fecha_fin" class="form-label">Fecha y Hora de Fin</label>
                    <input type="datetime-local" id="fecha_fin" name="fecha_fin" class="form-control" value="<?= isset($old['fecha_fin']) ? str_replace(' ', 'T', $old['fecha_fin']) : str_replace(' ', 'T', $reserva['fecha_fin']) ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="motivo_uso" class="form-label">Motivo del Uso</label>
                <input type="text" id="motivo_uso" name="motivo_uso" class="form-control" value="<?= htmlspecialchars($old['motivo_uso'] ?? $reserva['motivo_uso'] ?? '') ?>">
            </div>
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md);">
                <button type="submit" class="btn btn-primario">Guardar Cambios</button>
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas" class="btn btn-secundario">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../_footer.php'; ?>
