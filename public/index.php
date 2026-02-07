<?php
// iniciar sesión para manejo de autenticación
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/App.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Model.php';

$app = new App();
