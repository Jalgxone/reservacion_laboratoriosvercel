<?php
class AuthController extends Controller
{
    // muestra el formulario de login
    public function index()
    {
        // Si ya está autenticado, redirigir al dashboard
        if (!empty($_SESSION['user'])) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/dashboard');
            exit;
        }
        // mostrar formulario
        $this->view('auth/login');
    }

    // procesa el formulario POST
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }
        require_once __DIR__ . '/../../core/Validator.php';

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $data = ['email' => $email, 'password' => $password];
        $rules = [
            'email' => 'required|email',
            'password' => 'required|minlen:1'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $this->view('auth/login', ['errors' => $errors, 'email' => $email]);
            return;
        }

        $userModel = $this->model('Usuario');
        $user = $userModel ? $userModel->authenticate($email, $password) : false;

        if ($user) {
            // almacenar datos mínimos en sesión
            $_SESSION['user'] = [
                'id' => $user['id_usuario'],
                'nombre' => $user['nombre_completo'],
                'email' => $user['email'],
                'id_rol' => $user['id_rol']
            ];
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/dashboard');
            exit;
        }

        // error de login
        $_SESSION['error'] = 'Email o contraseña incorrectos.';
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
        exit;
    }

    public function dashboard()
    {
        if (empty($_SESSION['user'])) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }

        $this->view('auth/dashboard', ['user' => $_SESSION['user']]);
    }

    public function logout()
    {
        // destruir sesión
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
        exit;
    }

    // muestra formulario registro
    public function register()
    {
        if (!empty($_SESSION['user'])) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/dashboard');
            exit;
        }
        $this->view('auth/register');
    }

    // procesa registro
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/register');
            exit;
        }
        require_once __DIR__ . '/../../core/Validator.php';

        $nombre = trim($_POST['nombre_completo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $data = ['nombre_completo' => $nombre, 'email' => $email, 'password' => $password];
        $rules = [
            'nombre_completo' => 'required|minlen:3|maxlen:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|minlen:8|maxlen:128'
        ];
        $errors = Validator::validate($data, $rules);
        if ($password !== $confirm) {
            $errors['confirm_password'][] = 'Las contraseñas no coinciden.';
        }

        if (!empty($errors)) {
            $this->view('auth/register', ['errors' => $errors, 'nombre_completo' => $nombre, 'email' => $email]);
            return;
        }

        try {
            $userModel = $this->model('Usuario');
            $id = $userModel->create([
                'nombre_completo' => $nombre,
                'email' => $email,
                'password' => $password,
                'id_rol' => 1 // Cliente por defecto
            ]);

            if ($id) {
                $_SESSION['flash'] = 'Registro exitoso. Ya puedes iniciar sesión.';
                header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
                exit;
            }
        } catch (Exception $e) {
            $this->view('auth/register', ['errors' => ['general' => [$e->getMessage()]], 'nombre_completo' => $nombre, 'email' => $email]);
            return;
        }
    }

    // ver/editar perfil
    public function profile()
    {
        if (empty($_SESSION['user'])) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }

        $userModel = $this->model('Usuario');
        $userData = $userModel->getById($_SESSION['user']['id']);

        $this->view('auth/profile', ['user' => $userData]);
    }

    // actualizar perfil
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user'])) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/profile');
            exit;
        }
        require_once __DIR__ . '/../../core/Validator.php';

        $id = $_SESSION['user']['id'];
        $data = [
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        $rules = [
            'nombre_completo' => 'required|minlen:3|maxlen:255',
            'email' => 'required|email|maxlen:255'
        ];
        if (!empty($data['password'])) {
            $rules['password'] = 'minlen:8|maxlen:128';
        }

        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $userModel = $this->model('Usuario');
            $userData = $userModel->getById($id);
            $this->view('auth/profile', ['errors' => $errors, 'user' => array_merge($userData ?: [], $data)]);
            return;
        }

        try {
            $userModel = $this->model('Usuario');
            if ($userModel->update($id, $data)) {
                // actualizar sesión
                $_SESSION['user']['nombre'] = $data['nombre_completo'];
                $_SESSION['user']['email'] = $data['email'];
                $_SESSION['flash'] = 'Perfil actualizado correctamente.';
            }
        } catch (Exception $e) {
            $this->view('auth/profile', ['errors' => ['general' => [$e->getMessage()]], 'user' => array_merge($userModel->getById($id) ?: [], $data)]);
            return;
        }

        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/profile');
        exit;
    }

    // muestra formulario "olvido contraseña"
    public function forgotPassword()
    {
        $this->view('auth/forgot_password');
    }

    // procesa solicitud de reset
    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/forgotPassword');
            exit;
        }
        require_once __DIR__ . '/../../core/Validator.php';

        $email = trim($_POST['email'] ?? '');
        $data = ['email' => $email];
        $rules = ['email' => 'required|email'];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $this->view('auth/forgot_password', ['errors' => $errors, 'email' => $email]);
            return;
        }

        $userModel = $this->model('Usuario');
        $user = $userModel->getByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $userModel->setResetToken($email, $token, $expires);
            
            // En un sistema real aquí se enviaría un email. 
            // Para esta demo, mostraremos el link en un flash message.
            $link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?url=auth/resetPasswordForm&token=" . $token;
            $_SESSION['flash'] = "Simulación: Se ha generado un token. Link: <a href='$link'>Click aquí para resetear</a>";
        } else {
            $_SESSION['flash'] = "Si el correo existe en nuestro sistema, recibirás un link de recuperación.";
        }

        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth/forgotPassword');
        exit;
    }

    // muestra formulario de nueva contraseña
    public function resetPasswordForm()
    {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }

        $this->view('auth/reset_password', ['token' => $token]);
    }

    // procesa el cambio de contraseña
    public function handleResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }
        require_once __DIR__ . '/../../core/Validator.php';

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $data = ['password' => $password];
        $rules = ['password' => 'required|minlen:8|maxlen:128'];
        $errors = Validator::validate($data, $rules);
        if ($password !== $confirm) {
            $errors['confirm_password'][] = 'Las contraseñas no coinciden.';
        }
        if (!empty($errors)) {
            $this->view('auth/reset_password', ['errors' => $errors, 'token' => $token]);
            return;
        }

        $userModel = $this->model('Usuario');
        $user = $userModel->getUserByToken($token);

        if ($user) {
            $userModel->update($user['id_usuario'], ['password' => $password]);
            $userModel->clearResetToken($user['id_usuario']);
            $_SESSION['flash'] = 'Contraseña actualizada con éxito. Ya puedes iniciar sesión.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        } else {
            $this->view('auth/reset_password', ['errors' => ['general' => ['El token es inválido o ha expirado.']], 'token' => $token]);
            return;
        }
    }
    // Comprueba disponibilidad de email vía AJAX
    public function checkEmail()
    {
        $email = trim($_GET['email'] ?? '');
        if (empty($email)) {
            return $this->jsonResponse(['exists' => false]);
        }
        
        $userModel = $this->model('Usuario');
        $user = $userModel->getByEmail($email);
        
        return $this->jsonResponse(['exists' => ($user !== false)]);
    }
}
