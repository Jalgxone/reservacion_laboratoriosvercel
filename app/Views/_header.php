<!DOCTYPE html>
<html lang="es">
<head>
    <?php 
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $baseFull = rtrim(str_replace('\\', '/', $scriptDir), '/');
    $base = $baseFull;
    $appRoot = $baseFull;
    $v = time();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= (isset($title) ? $title . ' · ' : '') . 'LabReserva' ?></title>
    
    <link rel="icon" type="image/png" href="<?= $base ?>/logo.png?v=<?= $v ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $base ?>/favicon.ico?v=<?= $v ?>">
    <link rel="apple-touch-icon" href="<?= $base ?>/logo.png?v=<?= $v ?>">
    
    <link rel="stylesheet" href="<?= $base ?>/css/variables.css">
    <link rel="stylesheet" href="<?= $base ?>/css/base.css">
    <link rel="stylesheet" href="<?= $base ?>/css/components.css">
    <link rel="stylesheet" href="<?= $base ?>/css/layout.css">
    <script>
        window.showToast = function(message, type = 'info') {
            const main = document.querySelector('main');
            if (!main) return;

            // Limpiar alertas existentes del mismo tipo para evitar acumulación
            const existingAlerts = main.querySelectorAll(`.alert-static-block-${type}`);
            existingAlerts.forEach(alert => alert.remove());

            const container = document.createElement('div');
            container.className = `alert-static-block alert-static-block-${type}`;
            
            let title = "MENSAJE DEL SISTEMA";
            if (type === 'error') title = "NO SE PUDO PROCESAR LA SOLICITUD. POR FAVOR REVISE:";
            if (type === 'success') title = "OPERACIÓN EXITOSA. DETALLES:";
            if (type === 'warning') title = "ATENCIÓN REQUERIDA. FAVOR REVISAR:";

            container.innerHTML = `
                <div class="alert-static-block-title">${title}</div>
                <ul class="alert-static-block-list">
                    <li>${message}</li>
                </ul>
            `;
            
            main.insertBefore(container, main.firstChild);

            if (type === 'success' || type === 'info') {
                setTimeout(() => {
                    container.style.opacity = '0';
                    container.style.transition = 'opacity 0.5s';
                    setTimeout(() => container.remove(), 500);
                }, 5000);
            }
        };

        window.initPagination = function(bodyId, containerId, itemsPerPage = 5) {
            const body = document.getElementById(bodyId);
            const container = document.getElementById(containerId);
            if (!body || !container) return;

            const rows = Array.from(body.querySelectorAll('tr:not(.no-paginar)'));
            if (rows.length <= itemsPerPage) {
                container.innerHTML = '';
                return;
            }

            let currentPage = 1;

            function showPage(page) {
                currentPage = page;
                const start = (page - 1) * itemsPerPage;
                const end = start + itemsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? '' : 'none';
                });

                renderButtons();
            }

            function renderButtons() {
                const numPages = Math.ceil(rows.length / itemsPerPage);
                container.innerHTML = '';

                for (let i = 1; i <= numPages; i++) {
                    const btn = document.createElement('button');
                    btn.innerText = i;
                    btn.className = (i === currentPage) ? 'btn btn-primario btn-sm' : 'btn btn-secundario btn-sm';
                    btn.style.minWidth = '35px';
                    btn.onclick = () => {
                        showPage(i);
                        window.scrollTo({ top: body.closest('.card').offsetTop - 20, behavior: 'smooth' });
                    };
                    container.appendChild(btn);
                }
            }

            showPage(1);
        };
    </script>
</head>
<body style="min-height: 100vh; display: flex; flex-direction: column; margin: 0;">

    <nav class="navbar">
        <div class="contenedor navbar-contenido">
            <div class="navbar-brand" style="display: flex; align-items: center; gap: 10px; margin-right: 3rem;">
                <img src="<?= $base ?>/logo.png" alt="Logo" style="height: 32px; width: auto;">
                <span style="font-weight: bold; font-size: 1.25rem;">LabReserva</span>
            </div>
            <div class="navbar-menu" style="flex: 1; margin-left: var(--espacio-xl);">
                <?php 
                $currentUrlRaw = $_GET['url'] ?? ''; 
                $active = function($path) use ($currentUrlRaw) {
                    return (strpos($currentUrlRaw, $path) === 0) ? 'activo' : '';
                };
                ?>
                <?php if (!empty($_SESSION['user'])): ?>
                    <div style="display: flex; gap: var(--espacio-lg); flex: 1; justify-content: space-between;">
                        <div style="display: flex; gap: var(--espacio-lg);">
                            <a href="<?= $appRoot ?>/auth/dashboard" class="navbar-link <?= $active('auth/dashboard') ?>">Inicio</a>
                            <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                                <a href="<?= $appRoot ?>/laboratorios" class="navbar-link <?= $active('laboratorios') ?>">Laboratorios</a>
                            <?php endif; ?>
                            <a href="<?= $appRoot ?>/reservas" class="navbar-link <?= $active('reservas') ?>">Reservas</a>
                            <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                                <a href="<?= $appRoot ?>/inventarios" class="navbar-link <?= $active('inventarios') ?>">Inventario</a>
                            <?php endif; ?>
                            <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                                <a href="<?= $appRoot ?>/usuarios" class="navbar-link <?= $active('usuarios') ?>">Usuarios</a>
                            <?php endif; ?>
                            <a href="<?= $appRoot ?>/horarios" class="navbar-link <?= $active('horarios') ?>">Horarios</a>
                            <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                                <a href="<?= $appRoot ?>/recursos" class="navbar-link <?= $active('recursos') ?>">Recursos</a>
                                <a href="<?= $appRoot ?>/incidencias" class="navbar-link <?= $active('incidencias') ?>">Incidencias</a>
                            <?php else: ?>
                                <a href="<?= $appRoot ?>/incidencias/create" class="navbar-link <?= $active('incidencias/create') ?>">Reportar Incidencia</a>
                            <?php endif; ?>
                        </div>
                    <div class="dropdown">
                        <div class="dropdown-toggle" onclick="toggleUserMenu(event)">
                            <div style="text-align: right; margin-right: 8px;">
                                <div style="font-weight: 600; font-size: 0.9rem; color: var(--color-primario);">
                                    <?= $_SESSION['user']['nombre'] ?? 'Usuario' ?>
                                </div>
                                <div style="font-size: 0.75rem; color: var(--color-texto-claro);">
                                    <?= ($_SESSION['user']['id_rol'] == 2) ? 'Administrador' : 'Usuario' ?>
                                </div>
                            </div>
                            <div class="dashboard-card-icon" style="width: 36px; height: 36px; background-color: <?= ($_SESSION['user']['id_rol'] == 2) ? 'var(--color-primario)' : 'var(--color-acento)' ?>; color: white; border-radius: 50%;">
                                <?= strtoupper(substr($_SESSION['user']['nombre'] ?? 'U', 0, 1)) ?>
                            </div>
                        </div>
                        <div class="dropdown-menu" id="userMenu">
                            <div style="padding: var(--espacio-sm) var(--espacio-md); border-bottom: 1px solid var(--color-borde);">
                                <small style="color: var(--color-texto-claro); text-transform: uppercase; font-size: 0.7rem; font-weight: 700;">Cuenta</small>
                            </div>
                            <a href="<?= $appRoot ?>/auth/profile" class="dropdown-item">
                                Perfil
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?= $appRoot ?>/auth/logout" class="dropdown-item" style="color: var(--color-error);">
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        function toggleUserMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('userMenu');
            menu.classList.toggle('show');
        }


        document.addEventListener('click', function(event) {
            const menu = document.getElementById('userMenu');
            const toggle = document.querySelector('.dropdown-toggle');
            if (menu && menu.classList.contains('show') && !toggle.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.remove('show');
            }
        });
    </script>

    <main class="<?= ($fullWidth ?? false) ? 'full-width-main' : 'contenedor seccion-principal' ?>">
        
        <?php require_once __DIR__ . '/partials/alerts.php'; ?>
        
        <style>
            .full-width-main {
                width: 100%;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                margin: 0;
            }
            .seccion-principal {
                flex-grow: 1;
            }
        </style>
