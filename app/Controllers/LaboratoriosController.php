<?php
class LaboratoriosController extends Controller
{
    public function index()
    {
        // proteger ruta: requerir autenticación
        if (empty($_SESSION['user'])) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }
        $model = $this->model('Laboratorio');
        $labs = $model ? $model->getAll() : [];
        $this->view('laboratorios/index', ['labs' => $labs]);
    }

    public function create()
    {
        $this->requireRole([2]);
        $this->view('laboratorios/create');
    }

    public function store()
    {
        $this->requireRole([2]);

        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'ubicacion' => trim($_POST['ubicacion'] ?? ''),
            'capacidad' => $_POST['capacidad'] ?? 0,
            'esta_activo' => isset($_POST['esta_activo']) ? 1 : 0,
        ];

        $rules = [
            'nombre' => 'required|minlen:3|maxlen:255',
            'ubicacion' => 'required|maxlen:255',
            'capacidad' => 'required|int'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $this->view('laboratorios/create', ['errors' => $errors, 'nombre' => $data['nombre'], 'ubicacion' => $data['ubicacion'], 'capacidad' => $data['capacidad'], 'esta_activo' => $data['esta_activo']]);
            return;
        }

        $model = $this->model('Laboratorio');
        try {
            $model->create($data);
            $_SESSION['flash'] = 'Laboratorio creado correctamente.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=laboratorios');
            exit;
        } catch (Exception $e) {
            error_log('LaboratoriosController::store error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al crear laboratorio: ' . $e->getMessage();
            $this->view('laboratorios/create', ['errors' => [$e->getMessage()], 'nombre' => $data['nombre'], 'ubicacion' => $data['ubicacion'], 'capacidad' => $data['capacidad'], 'esta_activo' => $data['esta_activo']]);
        }
    }

    public function edit($id = null)
    {
        $this->requireRole([2]);

        $model = $this->model('Laboratorio');
        $lab = $model->getById($id);
        if (!$lab) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=laboratorios');
            exit;
        }
        $this->view('laboratorios/edit', ['lab' => $lab]);
    }

    public function update($id = null)
    {
        $this->requireRole([2]);

        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'nombre' => trim($_POST['nombre'] ?? ''),
            'ubicacion' => trim($_POST['ubicacion'] ?? ''),
            'capacidad' => $_POST['capacidad'] ?? 0,
            'esta_activo' => isset($_POST['esta_activo']) ? 1 : 0,
        ];

        $rules = [
            'nombre' => 'required|minlen:3|maxlen:255',
            'ubicacion' => 'required|maxlen:255',
            'capacidad' => 'required|int'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $model = $this->model('Laboratorio');
            $lab = $model->getById($id);
            $this->view('laboratorios/edit', ['errors' => $errors, 'lab' => array_merge($lab ?: [], $data)]);
            return;
        }

        $model = $this->model('Laboratorio');
        try {
            $model->update($id, $data);
            $_SESSION['flash'] = 'Laboratorio actualizado.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=laboratorios');
            exit;
        } catch (Exception $e) {
            error_log('LaboratoriosController::update error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al actualizar laboratorio: ' . $e->getMessage();
            $lab = $model->getById($id);
            $this->view('laboratorios/edit', ['errors' => [$e->getMessage()], 'lab' => $lab]);
        }
    }

    public function delete($id = null)
    {
        $this->requireRole([2]);

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

        $model = $this->model('Laboratorio');
        try {
            $model->delete($id);
            $msg = 'Laboratorio eliminado.';
            if ($isAjax) {
                return $this->jsonResponse(['success' => true, 'message' => $msg]);
            }
            $_SESSION['flash'] = $msg;
        } catch (Exception $e) {
            error_log('LaboratoriosController::delete error: ' . $e->getMessage());
            
            // Detectar error de integridad referencial (Foreign Key constraint)
            $msg = 'Error al eliminar laboratorio.';
            if ($e instanceof PDOException && $e->getCode() == '23000') {
                $msg = 'No se puede eliminar el laboratorio porque tiene recursos o reservas asociadas.';
            }

            if ($isAjax) {
                return $this->jsonResponse(['success' => false, 'message' => $msg], 400);
            }
            $_SESSION['flash'] = $msg;
        }
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=laboratorios');
        exit;
    }
}
