<?php
// Logical check removed as it's now handled inside the file after header
?>
<?php
$title = "Inventario | Sistema de Reservación";
require __DIR__ . '/../_header.php';

$items = $items ?? [];
$total = count($items);

// Helper para clase de badge según estado
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
        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios/create" class="btn btn-primario">Nuevo Equipo</a>
    </div>
</div>

<!-- Panel de Filtros y Búsqueda -->
<div class="card" style="margin-bottom: var(--espacio-lg);">
    <form id="filtro-inventario-form" method="get" action="<?= $_SERVER['SCRIPT_NAME'] ?>">
        <input type="hidden" name="url" value="inventarios">
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

<!-- Tabla de Inventario -->
<div class="card">
    <div class="card-header">
        <h3>Listado de Equipos</h3>
        <span class="badge badge-info">Total: <?= $total ?> Equipos</span>
    </div>
    <div class="tabla-contenedor">
        <table class="tabla">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre del Equipo</th>
                    <th>Ubicación</th>
                    <th>Estado</th>
                    <th>Última Revisión</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="inventario-body">
                <?php if ($total === 0): ?>
                    <tr id="no-hay-equipos"><td colspan="6" style="text-align: center; padding: var(--espacio-lg); color: var(--color-texto-claro);">No hay equipos registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($items as $it):
                        $codigo = $it['codigo_serial'] ?? '';
                        $nombre = $it['marca_modelo'] ?? ($it['nombre_categoria'] ?? '');
                        $ubic = $it['laboratorio'] ?? '';
                        $estado = $it['estado_operativo'] ?? '';
                        $fecha = $it['ultima_revision'] ?? ($it['fecha_revision'] ?? '-');
                        $badge = badge_class_for_inventario($estado);
                        $id = $it['id_equipo'] ?? '';
                        ?>
                        <tr class="item-inventario" data-codigo="<?= htmlspecialchars($codigo) ?>" data-nombre="<?= htmlspecialchars(strtolower($nombre)) ?>" data-ubicacion="<?= htmlspecialchars($ubic) ?>" data-estado="<?= htmlspecialchars($estado) ?>">
                            <td><code><?= htmlspecialchars($codigo) ?></code></td>
                            <td><strong><?= htmlspecialchars($nombre) ?></strong></td>
                            <td><?= htmlspecialchars($ubic) ?></td>
                            <td><span class="badge <?= $badge ?>"><?= htmlspecialchars($estado ?: '-') ?></span></td>
                            <td><?= htmlspecialchars($fecha) ?></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios/edit/<?= urlencode($id) ?>" class="btn btn-secundario btn-sm">Editar</a>
                                    <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios/delete/<?= urlencode($id) ?>" onclick="return confirm('¿Eliminar equipo?')" class="btn btn-error btn-sm">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Paginación -->
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

        // Limpiar mensaje de "No hay resultados" si existe
        const existingMsg = document.getElementById('item-no-resultados');
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
        // Ocultar todas las filas
        rows.forEach(r => r.style.display = 'none');

        const totalFiltered = filteredRows.length;
        
        if (totalFiltered === 0) {
            const tr = document.createElement('tr');
            tr.id = 'item-no-resultados';
            tr.innerHTML = '<td colspan="6" style="text-align: center; padding: var(--espacio-lg); color: var(--color-texto-claro);">Sin resultados coincidentes.</td>';
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

    // Inicializar
    filteredRows = [...rows];
    mostrarPagina();

    busqueda.addEventListener('input', filtrar);
    filtroEstado.addEventListener('change', filtrar);
    filtroUbicacion.addEventListener('change', filtrar);
});
</script>
