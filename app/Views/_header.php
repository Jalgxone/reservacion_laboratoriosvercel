<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistema de Reservación' ?></title>
    <!-- Vinculación de Estilos CSS -->
    <?php $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'); ?>
    <link rel="stylesheet" href="<?= $base ?>/css/variables.css">
    <link rel="stylesheet" href="<?= $base ?>/css/base.css">
    <link rel="stylesheet" href="<?= $base ?>/css/components.css">
    <link rel="stylesheet" href="<?= $base ?>/css/layout.css">
    <style>
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            min-width: 250px;
            padding: 12px 20px;
            border-radius: 8px;
            background: white;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideIn 0.3s ease forwards;
            border-left: 4px solid var(--color-primario);
            font-size: 0.9rem;
            color: var(--color-texto-oscuro);
        }
        .toast-success { border-left-color: var(--color-exito); }
        .toast-error { border-left-color: var(--color-peligro); }
        .toast-warning { border-left-color: var(--color-advertencia); }
        .toast-info { border-left-color: var(--color-acento); }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: scale(1); }
            to { opacity: 0; transform: scale(0.95); }
        }
        .pagination-container {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            padding: 10px;
        }
    </style>
    <script>
        window.showToast = function(message, type = 'info') {
            let container = document.querySelector('.toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'toast-container';
                document.body.appendChild(container);
            }
            
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icon = {
                'success': 'check-circle',
                'error': 'alert-circle',
                'warning': 'alert-triangle',
                'info': 'info'
            }[type] || 'info';

            toast.innerHTML = `
                <div class="toast-content">
                    ${message}
                </div>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3500);
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
<body>
    <!-- Barra de Navegación -->
    <nav class="navbar">
        <div class="contenedor navbar-contenido">
            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/dashboard" class="navbar-brand">
                Sistema Laboratorios
            </a>
            <div class="navbar-menu" style="flex: 1; margin-left: var(--espacio-xl);">
                <?php 
                $currentUrl = $_GET['url'] ?? ''; 
                $active = function($path) use ($currentUrl) {
                    return (strpos($currentUrl, $path) === 0) ? 'activo' : '';
                };
                ?>
                <?php if (!empty($_SESSION['user'])): ?>
                    <div style="display: flex; gap: var(--espacio-lg); flex: 1; justify-content: space-between;">
                        <div style="display: flex; gap: var(--espacio-lg);">
                            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/dashboard" class="navbar-link <?= $active('auth/dashboard') ?>">Dashboard</a>
                            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=laboratorios" class="navbar-link <?= $active('laboratorios') ?>">Laboratorios</a>
                            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=reservas" class="navbar-link <?= $active('reservas') ?>">Reservas</a>
                            <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=inventarios" class="navbar-link <?= $active('inventarios') ?>">Inventario</a>
                            <?php endif; ?>
                            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=horarios" class="navbar-link <?= $active('horarios') ?>">Horarios</a>
                            <?php if ($_SESSION['user']['id_rol'] == 2): ?>
                                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=recursos" class="navbar-link <?= $active('recursos') ?>">Recursos</a>
                                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=incidencias" class="navbar-link <?= $active('incidencias') ?>">Incidencias</a>
                            <?php else: ?>
                                <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=incidencias/create" class="navbar-link <?= $active('incidencias/create') ?>">Reportar Incidencia</a>
                            <?php endif; ?>
                        </div>
                        <div style="display: flex; gap: var(--espacio-lg);">
                            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/profile" class="navbar-link <?= $active('auth/profile') ?>">Perfil</a>
                            <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/logout" class="navbar-link" style="color: var(--color-error);">Salir</a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if ($currentUrl !== 'auth'): ?>
                        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth" class="navbar-link <?= $active('auth') ?>">Iniciar sesión</a>
                    <?php endif; ?>
                    <?php if ($currentUrl !== 'auth/register'): ?>
                        <a href="<?= $_SERVER['SCRIPT_NAME'] ?>?url=auth/register" class="navbar-link <?= $active('auth/register') ?>">Registrarse</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="contenedor seccion-principal">
        
        <?php if (!empty($_SESSION['flash'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const msg = <?= json_encode($_SESSION['flash']) ?>;
                    let type = 'success';
                    if (msg.toLowerCase().includes('error') || msg.toLowerCase().includes('denegado') || msg.toLowerCase().includes('no tiene')) {
                        type = 'error';
                    } else if (msg.toLowerCase().includes('simulación') || msg.toLowerCase().includes('atención')) {
                        type = 'warning';
                    }
                    window.showToast(msg, type);
                });
            </script>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
