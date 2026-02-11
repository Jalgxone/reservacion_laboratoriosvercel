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

    public function currentUser()
    {
        if (empty($_SESSION['user'])) return null;
        $u = $_SESSION['user'];
        if (isset($u['id_usuario']) && !isset($u['id'])) $u['id'] = $u['id_usuario'];
        if (isset($u['id']) && !isset($u['id_usuario'])) $u['id_usuario'] = $u['id'];
        return $u;
    }

    public function getAppRoot()
    {
        return rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    }

    public function redirect($url)
    {
        $target = $this->getAppRoot() . '/' . ltrim($url, '/');
        header('Location: ' . $target);
        exit;
    }

    public function requireRole(array $allowed = [])
    {
        $user = $this->currentUser();
        if (!$user) {
            $this->redirect('auth');
        }
        if (empty($allowed)) return true;
        $userRole = $user['id_rol'] ?? null;
        if (in_array($userRole, $allowed, true)) return true;
        $_SESSION['flash'] = 'Acceso denegado.';
        $this->redirect('auth/dashboard');
    }
    public function jsonResponse($data, $status = 200)
    {
        header_remove();
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
