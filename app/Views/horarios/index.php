<?php
$weekStart = $weekStart ?? date('Y-m-d', strtotime('last sunday'));
$weekEnd = $weekEnd ?? date('Y-m-d', strtotime('next saturday'));
$laboratorios = $laboratorios ?? [];
$reservas = $reservas ?? [];
$selectedLab = $selectedLab ?? null;
$prevWeek = $prevWeek ?? date('Y-m-d', strtotime('-1 week'));
$nextWeek = $nextWeek ?? date('Y-m-d', strtotime('+1 week'));
?>
<?php
$title = "Horarios";
require __DIR__ . '/../_header.php';
?>

<style>

    .calendario-header {
        display: grid;
        grid-template-columns: 80px repeat(7, 1fr);
        gap: 1px;
        background-color: var(--color-borde);
        border: 1px solid var(--color-borde);
        border-radius: var(--radio-borde) var(--radio-borde) 0 0;
        overflow: hidden;
    }

    .cal-col-header {
        background-color: var(--color-fondo);
        padding: var(--espacio-sm);
        text-align: center;
        font-weight: 600;
        color: var(--color-primario);
    }

    .calendario-body {
        display: grid;
        grid-template-columns: 80px repeat(7, 1fr);
        gap: 1px;
        background-color: var(--color-borde);
        border: 1px solid var(--color-borde);
        border-top: none;
    }

    .cal-hora {
        background-color: white;
        padding: var(--espacio-sm);
        text-align: center;
        font-size: 0.85rem;
        color: var(--color-texto-claro);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cal-celda {
        background-color: white;
        min-height: 50px;
        padding: var(--espacio-xs);
        transition: var(--transicion);
    }

    .cal-celda:hover {
        background-color: #f8fafc;
    }

    .evento {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
        margin-bottom: 4px;
        cursor: pointer;
        border-left: 3px solid;
        transition: var(--transicion);
    }

    .evento:hover {
        transform: scale(1.02);
        filter: brightness(0.95);
    }

    .evento-reservado {
        background-color: #e0f2fe;
        color: #0369a1;
        border-left: 3px solid #0ea5e9;
    }

    .evento-mantenimiento {
        background-color: #fee2e2;
        color: #991b1b;
        border-left: 3px solid #dc2626;
    }

    .evento strong {
        font-weight: 600;
        color: var(--color-texto-oscuro);
    }
</style>
        
        <div class="pagina-cabecera">
            <div>
                <h1 class="pagina-titulo">Calendario de Reservas</h1>
                <p class="pagina-subtitulo">Visualización semanal de disponibilidad de laboratorios</p>
            </div>
            
            <div class="filtros-horario" style="margin-bottom: var(--espacio-md); display: flex; align-items: center; gap: var(--espacio-md);">
                <form id="filtro-lab-form" method="get" action="<?= $appRoot ?>/horarios" style="display: flex; gap: 10px; align-items: center;" novalidate>
                    <input type="hidden" id="current-date" name="date" value="<?= $weekStart ?>">
                    <select id="select-lab" name="lab" class="form-control">
                        <?php foreach ($laboratorios as $l): ?>
                            <option value="<?= $l['id_laboratorio'] ?>" <?= ($selectedLab == $l['id_laboratorio']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                
                <div class="nav-semana">
                    <a href="<?= $appRoot ?>/horarios?date=<?= $prevWeek ?>&lab=<?= $selectedLab ?>" id="btn-prev-week" class="nav-btn">Anterior</a>
                    <span>|</span>
                    <a href="<?= $appRoot ?>/horarios?date=<?= $nextWeek ?>&lab=<?= $selectedLab ?>" id="btn-next-week" class="nav-btn">Siguiente</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 id="rango-fechas-titulo">Semana del <?= date('d/m', strtotime($weekStart)) ?> al <?= date('d/m', strtotime($weekEnd)) ?></h3>
                <div>
                     <span class="badge badge-info" style="margin-right: 10px;">Reservado</span>
                </div>
            </div>
            
            <div id="calendario-grid-container">
                <div class="calendario-header" id="calendario-header">
                    <div class="cal-col-header">Hora</div>
                    <?php 
                    $days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                    for ($i = 0; $i < 7; $i++): 
                        $curStr = strtotime("+$i days", strtotime($weekStart));
                        $curDate = date('d', $curStr);
                        $fullDate = date('Y-m-d', $curStr);
                    ?>
                        <div class="cal-col-header" data-date="<?= $fullDate ?>"><?= $days[$i] ?> <?= $curDate ?></div>
                    <?php endfor; ?>
                </div>
                <div class="calendario-body" id="calendario-body">
                    <?php 
                    for ($h = 7; $h <= 20; $h++): 
                        $timeStr = sprintf('%02d:00', $h);
                        $nextTimeStr = sprintf('%02d:00', $h + 1);
                    ?>
                        <div class="cal-hora"><?= $timeStr ?></div>
                        
                        <?php for ($d = 0; $d < 7; $d++): 
                            $currentDayDate = date('Y-m-d', strtotime("+$d days", strtotime($weekStart)));
                            
                            $slotStartTimestamp = strtotime("$currentDayDate $h:00:00");
                            $slotEndTimestamp = strtotime("$currentDayDate " . ($h+1) . ":00:00");


                            $cellEvents = [];
                            foreach ($reservas as $res) {
                                $resStartTimestamp = strtotime($res['fecha_inicio']);
                                $resEndTimestamp = strtotime($res['fecha_fin']);


                                if ($resStartTimestamp < $slotEndTimestamp && $resEndTimestamp > $slotStartTimestamp) {
                                    $cellEvents[] = $res;
                                }
                            }
                        ?>
                            <div class="cal-celda">
                                <?php foreach ($cellEvents as $evt): 
                                    $resStartTimestamp = strtotime($evt['fecha_inicio']);
                                    $isStart = ($resStartTimestamp >= $slotStartTimestamp && $resStartTimestamp < $slotEndTimestamp);

                                    $resStartTimestamp = strtotime($evt['fecha_inicio']);
                                    $isStart = ($resStartTimestamp >= $slotStartTimestamp && $resStartTimestamp < $slotEndTimestamp);
                                    if ($h == 7 && $resStartTimestamp < $slotStartTimestamp) $isStart = true;
                                    
                                    $motivo = strtolower($evt['motivo_uso'] ?? '');
                                    $estado = strtolower($evt['nombre_estado'] ?? '');
                                    $evtClass = 'evento-reservado';
                                    if (strpos($motivo, 'mantenimiento') !== false || strpos($motivo, 'limpieza') !== false || strpos($motivo, 'reparacion') !== false) {
                                        $evtClass = 'evento-mantenimiento';
                                    }
                                ?>
                                    <div class="evento <?= $evtClass ?>" title="<?= htmlspecialchars($evt['motivo_uso'] ?? '') ?>">
                                        <?php if ($isStart): ?>
                                            <div style="font-size: 0.75em; border-bottom: 1px solid rgba(0,0,0,0.1); margin-bottom: 2px;">
                                                <?= date('H:i', strtotime($evt['fecha_inicio'])) ?> - <?= date('H:i', strtotime($evt['fecha_fin'])) ?>
                                            </div>
                                            <strong><?= htmlspecialchars(substr($evt['usuario_nombre'], 0, 15)) ?>...</strong>
                                        <?php else: ?>
                                            <div style="font-size: 0.7em; color: rgba(0,0,0,0.5); text-align: center;">...</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endfor; ?>

                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: var(--espacio-lg);">
            <div class="card-header">
                <h3>Detalle de Reservas</h3>
            </div>
            <div id="detalle-reservas-container">
                <?php if (!empty($reservas)): ?>
                    <table class="tabla" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="padding: 12px; border-bottom: 2px solid var(--color-borde); text-align: left;">Laboratorio</th>
                                <th style="padding: 12px; border-bottom: 2px solid var(--color-borde); text-align: left;">Inicio</th>
                                <th style="padding: 12px; border-bottom: 2px solid var(--color-borde); text-align: left;">Fin</th>
                                <th style="padding: 12px; border-bottom: 2px solid var(--color-borde); text-align: left;">Usuario</th>
                                <th style="padding: 12px; border-bottom: 2px solid var(--color-borde); text-align: left;">Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $r): ?>
                                <tr>
                                    <td style="padding: 12px; border-bottom: 1px solid var(--color-borde);"><?= htmlspecialchars($r['laboratorio_nombre'] ?? '') ?></td>
                                    <td style="padding: 12px; border-bottom: 1px solid var(--color-borde);"><?= htmlspecialchars($r['fecha_inicio']) ?></td>
                                    <td style="padding: 12px; border-bottom: 1px solid var(--color-borde);"><?= htmlspecialchars($r['fecha_fin']) ?></td>
                                    <td style="padding: 12px; border-bottom: 1px solid var(--color-borde);"><?= htmlspecialchars($r['usuario_nombre'] ?? '') ?></td>
                                    <td style="padding: 12px; border-bottom: 1px solid var(--color-borde);"><?= htmlspecialchars($r['motivo_uso'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding: var(--espacio-md); color: var(--color-texto-claro);">No hay reservas para mostrar.</div>
                <?php endif; ?>
            </div>
        </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectLab = document.getElementById('select-lab');
    const btnPrev = document.getElementById('btn-prev-week');
    const btnNext = document.getElementById('btn-next-week');
    const gridBody = document.getElementById('calendario-body');
    const gridHeader = document.getElementById('calendario-header');
    const detalleContainer = document.getElementById('detalle-reservas-container');
    const rangoTitulo = document.getElementById('rango-fechas-titulo');
    const inputDate = document.getElementById('current-date');

    let isUpdating = false;
    function updateCalendar(url) {
        if (isUpdating) return;
        isUpdating = true;

        gridBody.style.opacity = '0.5';
        
        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            isUpdating = false;
            // ... rest of the logic

            const start = new Date(data.weekStart + 'T00:00:00');
            const end = new Date(data.weekEnd + 'T00:00:00');
            rangoTitulo.innerText = `Semana del ${start.getDate().toString().padStart(2, '0')}/${(start.getMonth()+1).toString().padStart(2, '0')} al ${end.getDate().toString().padStart(2, '0')}/${(end.getMonth()+1).toString().padStart(2, '0')}`;
            inputDate.value = data.weekStart;
            

            const baseUrl = '<?= $appRoot ?>/horarios';
            btnPrev.href = `${baseUrl}?date=${data.prevWeek}&lab=${data.selectedLab}`;
            btnNext.href = `${baseUrl}?date=${data.nextWeek}&lab=${data.selectedLab}`;
            

            const days = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            const headerHtml = ['<div class="cal-col-header">Hora</div>'];
            for(let i=0; i<7; i++) {
                const d = new Date(data.weekStart + 'T00:00:00');
                d.setDate(d.getDate() + i);
                const dayLabel = days[d.getDay()];
                const dayNum = d.getDate().toString().padStart(2, '0');
                const fullDate = d.toISOString().split('T')[0];
                headerHtml.push(`<div class="cal-col-header" data-date="${fullDate}">${dayLabel} ${dayNum}</div>`);
            }
            gridHeader.innerHTML = headerHtml.join('');


            let bodyHtml = '';
            for (let h = 7; h <= 20; h++) {
                const timeStr = h.toString().padStart(2, '0') + ':00';
                bodyHtml += `<div class="cal-hora">${timeStr}</div>`;
                
                for (let d = 0; d < 7; d++) {
                    const dObj = new Date(data.weekStart + 'T00:00:00');
                    dObj.setDate(dObj.getDate() + d);
                    const currentDayDate = dObj.toISOString().split('T')[0];
                    
                    const slotStart = new Date(`${currentDayDate}T${h.toString().padStart(2, '0')}:00:00`).getTime();
                    const slotEnd = new Date(`${currentDayDate}T${(h+1).toString().padStart(2, '0')}:00:00`).getTime();


                    const cellEvents = data.reservas.filter(res => {
                        const resStart = new Date(res.fecha_inicio.replace(' ', 'T')).getTime();
                        const resEnd = new Date(res.fecha_fin.replace(' ', 'T')).getTime();
                        return resStart < slotEnd && resEnd > slotStart;
                    });

                    bodyHtml += '<div class="cal-celda">';
                    cellEvents.forEach(evt => {
                        const resStart = new Date(evt.fecha_inicio.replace(' ', 'T')).getTime();
                        let isStart = (resStart >= slotStart && resStart < slotEnd);
                        if (h === 7 && resStart < slotStart) isStart = true;

                        const motivo = (evt.motivo_uso || '').toLowerCase();
                        let evtClass = 'evento-reservado';
                        if (motivo.includes('mantenimiento') || motivo.includes('limpieza') || motivo.includes('reparacion')) {
                            evtClass = 'evento-mantenimiento';
                        }

                        bodyHtml += `
                            <div class="evento ${evtClass}" title="${evt.motivo_uso || ''}">
                                ${isStart ? `
                                    <div style="font-size: 0.75em; border-bottom: 1px solid rgba(0,0,0,0.1); margin-bottom: 2px;">
                                        ${evt.fecha_inicio.split(' ')[1].substring(0,5)} - ${evt.fecha_fin.split(' ')[1].substring(0,5)}
                                    </div>
                                    <strong>${(evt.usuario_nombre || '').substring(0,15)}...</strong>
                                ` : `
                                    <div style="font-size: 0.7em; color: rgba(0,0,0,0.5); text-align: center;">...</div>
                                `}
                            </div>`;
                    });
                    bodyHtml += '</div>';
                }
            }
            gridBody.innerHTML = bodyHtml;
            gridBody.style.opacity = '1';

            if (data.reservas.length > 0) {
                window.showToast(`Se han cargado ${data.reservas.length} reservas.`, 'success');
                let tableHtml = `
                    <table class="tabla" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Laboratorio</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Usuario</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>`;
                data.reservas.forEach(r => {
                    tableHtml += `
                        <tr>
                            <td>${r.laboratorio_nombre || ''}</td>
                            <td>${r.fecha_inicio}</td>
                            <td>${r.fecha_fin}</td>
                            <td>${r.usuario_nombre || ''}</td>
                            <td>${r.motivo_uso || ''}</td>
                        </tr>`;
                });
                tableHtml += '</tbody></table>';
                detalleContainer.innerHTML = tableHtml;
            } else {
                window.showToast('No hay reservas para los criterios seleccionados.', 'info');
                detalleContainer.innerHTML = '<div style="padding: var(--espacio-md); color: var(--color-texto-claro);">No hay reservas para mostrar.</div>';
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            isUpdating = false;
            window.showToast('Error al conectar con el servidor.', 'error');
            gridBody.style.opacity = '1';
        });
    }

    selectLab.addEventListener('change', () => {
        const url = `<?= $appRoot ?>/horarios?lab=${selectLab.value}&date=${inputDate.value}`;
        updateCalendar(url);
    });

    [btnPrev, btnNext].forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            updateCalendar(btn.href);
            
        });
    });
});
</script>

<?php require __DIR__ . '/../_footer.php'; ?>
