<?php
$title = "Laboratorios | Sistema de Reservación";
require __DIR__ . '/../_header.php';
?>

<div class="pagina-cabecera">
    <div>
        <h1 class="pagina-titulo">Lista de Laboratorios</h1>
        <p class="pagina-subtitulo">Gestión de espacios físicos y laboratorios</p>
    </div>
    <?php if ($_SESSION['user']['id_rol'] == 2): ?>
    <div class="acciones">
        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios/create" class="btn btn-primario">Crear laboratorio</a>
    </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Laboratorios Registrados</h3>
    </div>
    
    <?php if (!empty($labs)): ?>
    <table class="tabla" style="width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th>Capacidad</th>
                <th>Activo</th>
                <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($labs as $lab): ?>
            <tr>
                <td><?= htmlspecialchars($lab['id_laboratorio']) ?></td>
                <td><strong><?= htmlspecialchars($lab['nombre']) ?></strong></td>
                <td><?= htmlspecialchars($lab['ubicacion']) ?></td>
                <td><?= htmlspecialchars($lab['capacidad_personas']) ?> personas</td>
                <td>
                    <?php if ($lab['esta_activo']): ?>
                        <span class="badge badge-success">Activo</span>
                    <?php else: ?>
                        <span class="badge badge-error">Inactivo</span>
                    <?php endif; ?>
                </td>
                <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios/edit/<?= (int)$lab['id_laboratorio'] ?>" class="btn btn-secundario btn-sm">Editar</a>
                        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios/delete/<?= (int)$lab['id_laboratorio'] ?>" class="btn btn-error btn-sm" onclick="return confirm('¿Eliminar laboratorio?')">Eliminar</a>
                    </div>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div style="padding: var(--espacio-md); color: var(--color-texto-claro);">No hay laboratorios registrados.</div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Interceptar clics en botones de eliminar
    const deleteButtons = document.querySelectorAll('.btn-error');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            // Solo si el botón es para eliminar (contiene la URL de borrar)
            if (!this.href.includes('url=laboratorios/delete')) return;
            
            e.preventDefault();
            
            if (!confirm('¿Está seguro de que desea eliminar este laboratorio?')) return;
            
            const url = this.href;
            const row = this.closest('tr');
            
            // Animación de desvanecimiento
            row.style.opacity = '0.5';
            row.style.transition = 'opacity 0.3s';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.showToast(data.message, 'success');
                    
                    // Remover fila con animación
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        // Si no quedan filas, mostrar mensaje de "No hay laboratorios"
                        const tbody = document.querySelector('tbody');
                        if (tbody && tbody.children.length === 0) {
                            location.reload(); 
                        }
                    }, 300);
                } else {
                    window.showToast(data.message, 'error');
                    row.style.opacity = '1';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.showToast('Ocurrió un error al procesar la solicitud.', 'error');
                row.style.opacity = '1';
            });
        });
    });
});
</script>

<?php require __DIR__ . '/../_footer.php'; ?>
