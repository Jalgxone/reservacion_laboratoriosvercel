<?php
$errors = $errors ?? [];
$old = $old ?? [];
$hideGlobalAlerts = true;
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Crear Nueva Reserva</h1>
        <p class="pagina-subtitulo">Solicitar espacio de laboratorio</p>
    </div>
</div>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <h3>Detalles de la Reservación</h3>
    </div>
    
    <div style="padding: var(--espacio-md);">

        <form method="post" action="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas/store" novalidate>
            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-md);">
                <div class="form-group">
                    <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio</label>
                    <div style="position: relative;">
                        <input type="datetime-local" id="fecha_inicio" name="fecha_inicio" class="form-control" style="padding-left: 3rem;" value="<?= !empty($old['fecha_inicio']) ? str_replace(' ', 'T', $old['fecha_inicio']) : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <?php showFieldError('fecha_inicio', $errors); ?>
                </div>
                
                <div class="form-group">
                    <label for="fecha_fin" class="form-label">Fecha y Hora de Fin</label>
                    <div style="position: relative;">
                        <input type="datetime-local" id="fecha_fin" name="fecha_fin" class="form-control" style="padding-left: 3rem;" value="<?= !empty($old['fecha_fin']) ? str_replace(' ', 'T', $old['fecha_fin']) : '' ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                    <?php showFieldError('fecha_fin', $errors); ?>
                </div>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: var(--espacio-md);">
                <div class="form-group">
                    <label for="id_laboratorio" class="form-label">Laboratorio</label>
                    <div style="position: relative;">
                        <select name="id_laboratorio" id="id_laboratorio" class="form-control" style="padding-left: 3rem;">
                                <?php foreach ($labs as $lab): ?>
                                    <option value="<?= $lab['id_laboratorio'] ?>" <?= (!empty($old['id_laboratorio']) && $old['id_laboratorio'] == $lab['id_laboratorio']) ? 'selected' : '' ?>><?= htmlspecialchars($lab['nombre']) ?></option>
                                <?php endforeach; ?>
                        </select>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    </div>
                    <?php showFieldError('id_laboratorio', $errors); ?>
                </div>
                

            </div>
            
            <div class="form-group">
                <label for="motivo_uso" class="form-label">Motivo del Uso</label>
                <div style="position: relative;">
                    <input type="text" id="motivo_uso" name="motivo_uso" class="form-control" style="padding-left: 3rem;" value="<?= htmlspecialchars($old['motivo_uso'] ?? '') ?>" placeholder="Ej. Práctica de Redes, Examen, etc.">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-texto-claro);"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                </div>
                <?php showFieldError('motivo_uso', $errors); ?>
            </div>
            
            <div style="margin-top: var(--espacio-lg); display: flex; gap: var(--espacio-md); flex-wrap: wrap; justify-content: flex-end;">
                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas" class="btn btn-secundario" style="min-width: 120px; text-align: center;">Cancelar</a>
                <button type="submit" class="btn btn-primario" style="min-width: 160px;">Confirmar Reserva</button>

                <?php 
                $inlineMsgs = getSystemMessages();
                if (!empty($inlineMsgs)): 
                    foreach ($inlineMsgs as $m): ?>
                        <div class="alert-inline alert-inline-<?= $m['type'] ?>" style="width: 100%; margin-top: 1rem; flex-basis: 100%;">
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
