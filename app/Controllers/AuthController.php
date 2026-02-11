<?php
class AuthController extends Controller
{

    public function index()
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('auth/dashboard');
        }

        if (isset($_COOKIE['remember_me'])) {
            require_once __DIR__ . '/../../core/Security.php';
            $userId = Security::decrypt($_COOKIE['remember_me']);
            if ($userId) {
                $userModel = $this->model('Usuario');
                $user = $userModel->getById($userId);
                if ($user && $user['estado'] === 'activo') {
                    $_SESSION['user'] = [
                        'id' => $user['id_usuario'],
                        'nombre' => $user['nombre_completo'],
                        'email' => $user['email'],
                        'id_rol' => $user['id_rol']
                    ];
                    $this->redirect('auth/dashboard');
                }
            }
        }

        $this->view('auth/login');
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth');
        }
        require_once __DIR__ . '/../../core/Validator.php';

        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $data = ['email' => $email, 'password' => $password];
        $rules = [
            'email' => 'required|email',
            'password' => 'required|minlen:8'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $this->view('auth/login', ['errors' => $errors, 'email' => $email]);
            return;
        }

        $userModel = $this->model('Usuario');
        $user = $userModel ? $userModel->authenticate($email, $password) : false;

        if ($user) {
            if ($user['estado'] !== 'activo') {
                $mensaje = $user['estado'] === 'pendiente' 
                    ? 'Tu cuenta aún no ha sido aprobada por el administrador.' 
                    : 'Tu cuenta ha sido desactivada. Contacta al soporte.';
                $_SESSION['error'] = $mensaje;
                $this->redirect('auth');
            }

            $_SESSION['user'] = [
                'id' => $user['id_usuario'],
                'nombre' => $user['nombre_completo'],
                'email' => $user['email'],
                'id_rol' => $user['id_rol']
            ];

            if (isset($_POST['remember'])) {
                require_once __DIR__ . '/../../core/Security.php';
                $encryptedId = Security::encrypt($user['id_usuario']);
                setcookie('remember_me', $encryptedId, time() + (30 * 24 * 60 * 60), '/', '', isset($_SERVER['HTTPS']), true);
            }

            $this->redirect('auth/dashboard');
        }

        $_SESSION['error'] = 'Email o contraseña incorrectos.';
        $this->redirect('auth');
    }

    public function dashboard()
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('auth');
        }
        $this->view('auth/dashboard', ['user' => $_SESSION['user']]);
    }

    public function logout()
    {
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/');
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']
            );
        }
        session_destroy();        $this->redirect('auth');
    }


    public function register()
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('auth/dashboard');
        }
        $this->view('auth/register');
    }


    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/register');
        }
        require_once __DIR__ . '/../../core/Validator.php';

        $nombre = trim($_POST['nombre_completo'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $cedula = trim($_POST['cedula'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $data = [
            'nombre_completo' => $nombre,
            'apellido' => $apellido,
            'cedula' => $cedula,
            'telefono' => $telefono,
            'email' => $email,
            'password' => $password
        ];
        $rules = [
            'nombre_completo' => 'required|minlen:3|maxlen:255',
            'apellido' => 'required|minlen:3|maxlen:100',
            'cedula' => 'required|ve_ci|unique:usuarios,cedula_identidad',
            'telefono' => 'required|ve_phone',
            'email' => 'required|email|common_email|unique:usuarios,email',
            'password' => 'required|minlen:8|maxlen:128'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $this->view('auth/register', ['errors' => $errors, 'nombre_completo' => $nombre, 'apellido' => $apellido, 'cedula' => $cedula, 'telefono' => $telefono, 'email' => $email]);
            return;
        }

        try {
            $userModel = $this->model('Usuario');
            $id = $userModel->create([
                'nombre_completo' => $nombre,
                'apellido' => $apellido,
                'cedula' => $cedula,
                'telefono' => $telefono,
                'email' => $email,
                'password' => $password,
                'id_rol' => 1 // Cliente por defecto
            ]);
            if ($id) {
                $_SESSION['flash'] = 'Registro exitoso. Tu cuenta está pendiente de aprobación por el administrador. Se te notificará cuando puedas acceder.';
                $this->redirect('auth');
            }
        } catch (Exception $e) {
            $this->view('auth/register', ['errors' => ['general' => [$e->getMessage()]], 'nombre_completo' => $nombre, 'email' => $email]);
            return;
        }
    }


    public function profile()
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('auth');
        }

        $userModel = $this->model('Usuario');
        $userData = $userModel->getById($_SESSION['user']['id']);
        $stats = $userModel->getStats($_SESSION['user']['id']);
        $this->view('auth/profile', [
            'user' => $userData,
            'stats' => $stats
        ]);
    }


    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user'])) {
            $this->redirect('auth/profile');
        }
        require_once __DIR__ . '/../../core/Validator.php';

        $id = $_SESSION['user']['id'];
        $userModel = $this->model('Usuario');
        $currentUser = $userModel->getById($id);

        $data = [
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'cedula_identidad' => trim($_POST['cedula_identidad'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'current_password' => $_POST['current_password'] ?? ''
        ];

        if (!empty($_POST['new_password'])) {
            $data['password'] = $_POST['new_password'];
            $data['confirm_password'] = $_POST['confirm_password'] ?? '';
        }

        $rules = [
            'nombre_completo' => 'required|minlen:3|maxlen:255',
            'apellido' => 'required|minlen:3|maxlen:100',
            'email' => "required|email|common_email|maxlen:255|unique:usuarios,email,id_usuario,{$id}",
            'cedula_identidad' => "required|ve_ci|unique:usuarios,cedula_identidad,id_usuario,{$id}",
            'telefono' => 'required|ve_phone',
            'current_password' => 'required'
        ];

        if (!empty($data['password'])) {
            $rules['password'] = 'minlen:8|maxlen:128';
        }

        $errors = Validator::validate($data, $rules, [
            'current_password' => 'contraseña actual',
            'new_password' => 'nueva contraseña',
            'confirm_password' => 'confirmación de contraseña',
            'cedula_identidad' => 'cédula de identidad'
        ]);

        require_once __DIR__ . '/../../core/Security.php';
        if (empty($errors['current_password']) && !Security::verifyPassword($data['current_password'], $currentUser['password_hash'])) {
            $errors['current_password'][] = 'La contraseña actual es incorrecta.';
        }

        if (!empty($data['password']) && $data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'][] = 'La confirmación de la contraseña no coincide.';
        }

        if (!empty($errors)) {
            $stats = $userModel->getStats($id);
            $this->view('auth/profile', [
                'errors' => $errors, 
                'user' => array_merge($currentUser ?: [], $data),
                'stats' => $stats
            ]);
            return;
        }

        try {
            $updateData = $data;
            unset($updateData['current_password'], $updateData['confirm_password']);

            if ($userModel->update($id, $updateData)) {
                $_SESSION['user']['nombre'] = $data['nombre_completo'];
                $_SESSION['user']['email'] = $data['email'];
                $_SESSION['flash'] = 'Perfil actualizado correctamente.';
            }
        } catch (Exception $e) {
            $stats = $userModel->getStats($id);
            $this->view('auth/profile', [
                'errors' => ['general' => [$e->getMessage()]], 
                'user' => array_merge($currentUser ?: [], $data),
                'stats' => $stats
            ]);
            return;
        }

        $this->redirect('auth/profile');
    }


    public function forgotPassword()
    {
        $this->view('auth/forgot_password');
    }


    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth/forgotPassword');
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
                        $link = "http://" . $_SERVER['HTTP_HOST'] . $this->getAppRoot() . "/auth/resetPasswordForm?token=" . $token;
            $_SESSION['flash'] = "Simulación: Se ha generado un token. Link: <a href='$link'>Click aquí para resetear</a>";
        } else {
            $_SESSION['flash'] = "Si el correo existe en nuestro sistema, recibirás un link de recuperación.";
        }        $this->redirect('auth/forgotPassword');
    }


    public function resetPasswordForm()
    {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            $this->redirect('auth');
        }
        $this->view('auth/reset_password', ['token' => $token]);
    }


    public function handleResetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth');
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
            $this->redirect('auth');
        } else {
            $this->view('auth/reset_password', ['errors' => ['general' => ['El token es inválido o ha expirado.']], 'token' => $token]);
            return;
        }
    }

    public function checkEmail()
    {
        $email = trim($_GET['email'] ?? '');        if (empty($email)) {
            return $this->jsonResponse(['exists' => false]);
        }
        
        $userModel = $this->model('Usuario');
        $user = $userModel->getByEmail($email);
                return $this->jsonResponse(['exists' => ($user !== false)]);
    }
}
