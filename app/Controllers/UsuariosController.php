<?php
class UsuariosController extends Controller
{
    public function index()
    {
        $this->requireRole([2]);
        $model = $this->model('Usuario');
        $usuarios = $model ? $model->getAll() : [];
        $this->view('usuarios/index', ['usuarios' => $usuarios]);
    }

    public function create()
    {
        $this->requireRole([2]);
        $rolModel = $this->model('Rol');
        $roles = $rolModel ? $rolModel->getAll() : [];
        $this->view('usuarios/create', ['roles' => $roles]);
    }

    public function store()
    {
        $this->requireRole([2]);
        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'cedula' => trim($_POST['cedula_identidad'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'id_rol' => $_POST['id_rol'] ?? 1,
        ];

        $rules = [
            'nombre_completo' => 'required|minlen:3|maxlen:100',
            'cedula' => 'required|ve_ci|unique:usuarios,cedula_identidad',
            'telefono' => 'required|ve_phone',
            'email' => 'required|email|common_email|unique:usuarios,email',
            'password' => 'required|minlen:8',
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $rolModel = $this->model('Rol');
            $roles = $rolModel ? $rolModel->getAll() : [];
            $this->view('usuarios/create', ['roles' => $roles, 'old' => $data, 'errors' => $errors]);
            return;
        }

        $model = $this->model('Usuario');
        try {
            $model->create($data);
            $_SESSION['flash'] = 'Usuario creado correctamente.';
            $this->redirect('usuarios');
        } catch (Exception $e) {
            $rolModel = $this->model('Rol');
            $roles = $rolModel ? $rolModel->getAll() : [];
            $this->view('usuarios/create', ['errors' => [$e->getMessage()], 'roles' => $roles, 'old' => $data]);
        }
    }

    public function edit($id = null)
    {
        $this->requireRole([2]);
        $model = $this->model('Usuario');
        $user = $model->getById($id);
        if (!$user) {
            $this->redirect('usuarios');
        }
        $rolModel = $this->model('Rol');
        $roles = $rolModel ? $rolModel->getAll() : [];
        $this->view('usuarios/edit', ['user' => $user, 'roles' => $roles]);
    }

    public function update($id = null)
    {
        $this->requireRole([2]);
        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'nombre_completo' => trim($_POST['nombre_completo'] ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'cedula' => trim($_POST['cedula_identidad'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'id_rol' => $_POST['id_rol'] ?? 1,
        ];

        $rules = [
            'nombre_completo' => 'required|minlen:3|maxlen:100',
            'cedula' => "required|ve_ci|unique:usuarios,cedula_identidad,id_usuario,{$id}",
            'telefono' => 'required|ve_phone',
            'email' => "required|email|common_email|unique:usuarios,email,id_usuario,{$id}",
        ];
        if (!empty($data['password'])) {
            $rules['password'] = 'minlen:8';
        }
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $model = $this->model('Usuario');
            $user = $model->getById($id);
            $rolModel = $this->model('Rol');
            $roles = $rolModel ? $rolModel->getAll() : [];
            $this->view('usuarios/edit', ['user' => array_merge($user ?: [], $data), 'roles' => $roles, 'errors' => $errors]);
            return;
        }

        $model = $this->model('Usuario');
        try {
            $updateData = [
                'nombre_completo' => $data['nombre_completo'],
                'apellido' => $data['apellido'],
                'cedula_identidad' => $data['cedula'],
                'telefono' => $data['telefono'],
                'email' => $data['email'],
                'id_rol' => $data['id_rol']
            ];
            if (!empty($data['password'])) {
                $updateData['password'] = $data['password'];
            }
            if ($_SESSION['user_id'] == $id) {
                if (isset($updateData['id_rol']) && $updateData['id_rol'] != 2) {
                    throw new Exception('No puedes quitarte tus propios privilegios de administrador.');
                }
                if (isset($updateData['estado']) && $updateData['estado'] !== 'activo') {
                    throw new Exception('No puedes desactivar tu propia cuenta de administrador.');
                }
            }

            $model->update($id, $updateData);
            $_SESSION['flash'] = 'Usuario actualizado.';
            $this->redirect('usuarios');
        } catch (Exception $e) {
            $user = $model->getById($id);
            $rolModel = $this->model('Rol');
            $roles = $rolModel ? $rolModel->getAll() : [];
            $this->view('usuarios/edit', ['errors' => [$e->getMessage()], 'user' => $user, 'roles' => $roles]);
        }
    }

    public function delete($id = null)
    {
        $this->requireRole([2]);
        
        if (!$id) {
            $this->redirect('usuarios');
        }

        if ($_SESSION['user_id'] == $id) {
            $_SESSION['error'] = 'No puedes desactivar tu propia cuenta de administrador.';
            $this->redirect('usuarios');
            return;
        }

        $userModel = $this->model('Usuario');
        if ($userModel->delete($id)) {
            $_SESSION['success'] = 'El usuario ha sido desactivado exitosamente.';
        } else {
            $_SESSION['error'] = 'No se pudo desactivar el usuario.';
        }
        
        $this->redirect('usuarios');
    }

    public function approve($id = null)
    {
        $this->requireRole([2]);
        $model = $this->model('Usuario');
        try {
            if ($model->update($id, ['estado' => 'activo'])) {
                $_SESSION['flash'] = 'Usuario aprobado correctamente.';
            } else {
                $_SESSION['error'] = 'No se pudo aprobar al usuario.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
        $this->redirect('usuarios');
    }

    public function toggleStatus($id = null)
    {
        $this->requireRole([2]);
        $model = $this->model('Usuario');
        $user = $model->getById($id);
        if (!$user) {
            $this->redirect('usuarios');
        }

        $newStatus = ($user['estado'] === 'activo') ? 'inactivo' : 'activo';
        try {
            if ($model->update($id, ['estado' => $newStatus])) {
                $_SESSION['flash'] = 'Estado del usuario actualizado a ' . $newStatus . '.';
            } else {
                $_SESSION['error'] = 'No se pudo cambiar el estado.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
        $this->redirect('usuarios');
    }
}
