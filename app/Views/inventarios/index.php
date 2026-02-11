<?php
?>
<?php
$title = "Inventario";
require __DIR__ . '/../_header.php';

$items = $items ?? [];
$total = count($items);


function badge_class_for_inventario($estado)
{
    $e = strtolower((string)$estado);
    if (strpos($e, 'oper') !== false || strpos($e, 'dispon') !== false || $e === 'operativo') return 'badge-success';
    if (strpos($e, 'uso') !== false || strpos($e, 'en uso') !== false) return 'badge-warning';
    if (strpos($e, 'repar') !== false || strpos($e, 'manten') !== false || $e === 'en reparaciòn' || $e === 'en reparación') return 'badge-error';
    if ($e === 'baja') return 'badge-error';
    return 'badge-info';
}
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Gestión de Inventario</h1>
        <p class="pagina-subtitulo">Administración y control de equipos de laboratorio</p>
    </div>
    <div class="acciones">
        <a href="<?= $appRoot ?>/inventarios/create" class="btn btn-primario">Nuevo Equipo</a>
    </div>
</div>


<div class="card" style="margin-bottom: var(--espacio-lg);">
    <form id="filtro-inventario-form" method="get" action="<?= $appRoot ?>/inventarios">
        <div class="grid" style="grid-template-columns: repeat(3, 1fr); gap: var(--espacio-md);">
            <div class="form-group">
                <label class="form-label">Buscar Equipo</label>
                <input type="text" id="busqueda-inventario" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="form-control" placeholder="Ej: Microscopio, Proyector...">
            </div>
            <div class="form-group">
                <label class="form-label">Filtrar por Estado</label>
                <select id="filtro-estado" name="estado" class="form-control">
                    <option value="">Todos los estados</option>
                    <option value="Operativo" <?= (isset($_GET['estado']) && $_GET['estado'] === 'Operativo') ? 'selected' : '' ?>>Operativo</option>
                    <option value="En Reparación" <?= (isset($_GET['estado']) && $_GET['estado'] === 'En Reparación') ? 'selected' : '' ?>>En Reparación</option>
                    <option value="Baja" <?= (isset($_GET['estado']) && $_GET['estado'] === 'Baja') ? 'selected' : '' ?>>Baja</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Ubicación</label>
                <select id="filtro-ubicacion" name="lab" class="form-control">
                    <option value="">Todas las ubicaciones</option>
                    <?php
                    $labsSeen = [];
                    foreach ($items as $it) {
                        $lab = $it['laboratorio'] ?? '';
                        if ($lab && !in_array($lab, $labsSeen, true)) $labsSeen[] = $lab;
                    }
                    foreach ($labsSeen as $labOpt): ?>
                        <option value="<?= htmlspecialchars($labOpt) ?>" <?= (isset($_GET['lab']) && $_GET['lab'] === $labOpt) ? 'selected' : '' ?>><?= htmlspecialchars($labOpt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
</div>


<div class="card">
    <div class="card-header">
        <h3>Listado de Equipos</h3>
        <span class="badge badge-info">Total: <?= $total ?> Equipos</span>
    </div>
    <div class="tabla-contenedor">
        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Equipo</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Activo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="inventario-body">
                <?php if ($total === 0): ?>
                    <tr id="no-hay-equipos"><td colspan="5" style="text-align: center; padding: var(--espacio-lg); color: var(--color-texto-claro);">No hay equipos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($items as $it):
                        $id_inv = $it['codigo_serial'] ?? '';
                        $nombre = $it['marca_modelo'] ?? ($it['nombre_categoria'] ?? '');
                        $ubic = $it['laboratorio'] ?? '';
                        $estado = $it['estado_operativo'] ?? '';
                        $fecha = $it['ultima_revision'] ?? ($it['fecha_revision'] ?? '-');
                        $badge = badge_class_for_inventario($estado);
                        $id = $it['id_equipo'] ?? '';
                        ?>
                        <tr class="item-inventario" data-codigo="<?= htmlspecialchars($id_inv) ?>" data-nombre="<?= htmlspecialchars(strtolower($nombre)) ?>" data-ubicacion="<?= htmlspecialchars($ubic) ?>" data-estado="<?= htmlspecialchars($estado) ?>">
                            <td><code><?= htmlspecialchars($id_inv) ?></code></td>
                            <td><strong><?= htmlspecialchars($nombre) ?></strong></td>
                            <td><?= htmlspecialchars($ubic) ?></td>
                            <td><span class="badge <?= $badge ?>"><?= htmlspecialchars($estado ?: '-') ?></span></td>
                            <td>
                                <?php if ($it['esta_activo']): ?>
                                    <span class="badge badge-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge badge-error">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="<?= $appRoot ?>/inventarios/edit/<?= urlencode($id) ?>" class="btn btn-secundario btn-sm" title="Editar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <a href="<?= $appRoot ?>/inventarios/toggleStatus/<?= urlencode($id) ?>" class="btn btn-secundario btn-sm" title="<?= $it['esta_activo'] ? 'Desactivar' : 'Activar' ?>">
                                        <?php if ($it['esta_activo']): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line></svg>
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div id="paginacion-inventario" class="contenedor" style="display: flex; justify-content: center; gap: 10px; margin-top: 20px;"></div>

<?php require __DIR__ . '/../_footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const busqueda = document.getElementById('busqueda-inventario');
    const filtroEstado = document.getElementById('filtro-estado');
    const filtroUbicacion = document.getElementById('filtro-ubicacion');
    const rows = Array.from(document.querySelectorAll('.item-inventario'));
    const body = document.getElementById('inventario-body');
    const paginationContainer = document.getElementById('paginacion-inventario');
    
    const ITEMS_PER_PAGE = 5;
    let currentPage = 1;
    let filteredRows = [];

    function filtrar() {
        const q = busqueda.value.toLowerCase().trim();
        const est = filtroEstado.value;
        const ubi = filtroUbicacion.value;

        if (existingMsg) existingMsg.remove();

        filteredRows = rows.filter(row => {
            const dataNombre = row.dataset.nombre;
            const dataCodigo = row.dataset.codigo.toLowerCase();
            const dataEstado = row.dataset.estado;
            const dataUbicacion = row.dataset.ubicacion;

            const matchesBusqueda = dataNombre.includes(q) || dataCodigo.includes(q);
            const matchesEstado = (est === '' || dataEstado === est);
            const matchesUbicacion = (ubi === '' || dataUbicacion === ubi);

            return matchesBusqueda && matchesEstado && matchesUbicacion;
        });

        currentPage = 1;
        mostrarPagina();
    }

    function mostrarPagina() {
        rows.forEach(r => r.style.display = 'none');

        const totalFiltered = filteredRows.length;
        
        if (totalFiltered === 0) {
            const tr = document.createElement('tr');
            tr.id = 'item-no-resultados';
            tr.innerHTML = '<td colspan="5" style="text-align: center; padding: var(--espacio-lg); color: var(--color-texto-claro);">Sin resultados coincidentes.</td>';
            body.appendChild(tr);
            paginationContainer.innerHTML = '';
            return;
        }

        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        const end = start + ITEMS_PER_PAGE;
        const pageItems = filteredRows.slice(start, end);

        pageItems.forEach(row => row.style.display = '');
        
        renderPagination(totalFiltered);
    }

    function renderPagination(total) {
        const numPages = Math.ceil(total / ITEMS_PER_PAGE);
        paginationContainer.innerHTML = '';

        if (numPages <= 1) return;

        for (let i = 1; i <= numPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.className = (i === currentPage) ? 'btn btn-primario btn-sm' : 'btn btn-secundario btn-sm';
            btn.addEventListener('click', () => {
                currentPage = i;
                mostrarPagina();
                window.scrollTo({ top: document.querySelector('.card').offsetTop - 20, behavior: 'smooth' });
            });
            paginationContainer.appendChild(btn);
        }
    }

    filteredRows = [...rows];
    mostrarPagina();

    busqueda.addEventListener('input', filtrar);
    filtroEstado.addEventListener('change', filtrar);
    filtroUbicacion.addEventListener('change', filtrar);
});
</script>
