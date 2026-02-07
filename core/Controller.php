<?php
class Controller
{
    public function model($model)
    {
        $file = __DIR__ . '/../app/Models/' . $model . '.php';
        if (file_exists($file)) {
            require_once $file;
            return new $model();
        }
        return null;
    }

    public function view($view, $data = [])
    {
        $viewFile = __DIR__ . '/../app/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            extract($data);
            require $viewFile;
        } else {
            echo "View not found: " . $viewFile;
        }
    }

    // Retorna el usuario actual normalizado o null
    public function currentUser()
    {
        if (empty($_SESSION['user'])) return null;
        $u = $_SESSION['user'];
        // Normalizar claves comunes
        if (isset($u['id_usuario']) && !isset($u['id'])) $u['id'] = $u['id_usuario'];
        if (isset($u['id']) && !isset($u['id_usuario'])) $u['id_usuario'] = $u['id'];
        return $u;
    }

    // Requiere que el usuario tenga uno de los roles permitidos (por nombre o id)
    public function requireRole(array $allowed = [])
    {
        $user = $this->currentUser();
        if (!$user) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }
        if (empty($allowed)) return true;
        // comparar por id_rol o nombre de rol si el controlador pre-carga la info
        $userRole = $user['id_rol'] ?? null;
        if (in_array($userRole, $allowed, true)) return true;
        // Si allowed contiene nombres, es responsabilidad del controlador usar ids apropiados
        // Denegar acceso por defecto
        $_SESSION['flash'] = 'Acceso denegado.';
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/dashboard');
        exit;
    }
    // Retorna una respuesta en formato JSON
    public function jsonResponse($data, $status = 200)
    {
        header_remove();
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
