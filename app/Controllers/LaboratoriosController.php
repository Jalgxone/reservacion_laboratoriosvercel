<?php
class LaboratoriosController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('auth');
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
            'capacidad_personas' => $_POST['capacidad_personas'] ?? 0,
            'esta_activo' => isset($_POST['esta_activo']) ? 1 : 0,
        ];

        $rules = [
            'nombre' => 'required|alphanumeric_special|minlen:3|maxlen:255',
            'ubicacion' => 'required|alphanumeric_special|maxlen:255',
            'capacidad_personas' => 'required|int|min_val:10|max_val:50'
        ];
        $errors = Validator::validate($data, $rules, [
            'nombre' => 'nombre del laboratorio',
            'ubicacion' => 'ubicación',
            'capacidad_personas' => 'capacidad'
        ]);
        if (!empty($errors)) {
            $this->view('laboratorios/create', [
                'errors' => $errors, 
                'nombre' => $data['nombre'], 
                'ubicacion' => $data['ubicacion'], 
                'capacidad_personas' => $data['capacidad_personas'], 
                'esta_activo' => $data['esta_activo']
            ]);
            return;
        }

        $model = $this->model('Laboratorio');
        try {
            $model->create($data);
            $_SESSION['flash'] = 'Laboratorio creado correctamente.';
            $this->redirect('laboratorios');
        } catch (Exception $e) {
            error_log('LaboratoriosController::store error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al crear laboratorio: ' . $e->getMessage();
            $this->view('laboratorios/create', [
                'errors' => [$e->getMessage()], 
                'nombre' => $data['nombre'], 
                'ubicacion' => $data['ubicacion'], 
                'capacidad_personas' => $data['capacidad_personas'], 
                'esta_activo' => $data['esta_activo']
            ]);
        }
    }

    public function edit($id = null)
    {
        $this->requireRole([2]);

        $model = $this->model('Laboratorio');
        $lab = $model->getById($id);
        if (!$lab) {
            $this->redirect('laboratorios');
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
            'capacidad_personas' => $_POST['capacidad_personas'] ?? 0,
            'esta_activo' => isset($_POST['esta_activo']) ? 1 : 0,
        ];

        $rules = [
            'nombre' => 'required|alphanumeric_special|minlen:3|maxlen:255',
            'ubicacion' => 'required|alphanumeric_special|maxlen:255',
            'capacidad_personas' => 'required|int|min_val:10|max_val:50'
        ];
        $errors = Validator::validate($data, $rules, [
            'nombre' => 'nombre del laboratorio',
            'ubicacion' => 'ubicación',
            'capacidad_personas' => 'capacidad'
        ]);
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
            $this->redirect('laboratorios');
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
            

            $msg = 'Error al eliminar laboratorio.';
            if ($e instanceof PDOException && $e->getCode() == '23000') {
                $msg = 'No se puede eliminar el laboratorio porque tiene recursos o reservas asociadas.';
            }

            if ($isAjax) {
                return $this->jsonResponse(['success' => false, 'message' => $msg], 400);
            }
            $_SESSION['flash'] = $msg;
        }
        $this->redirect('laboratorios');
    }

    public function toggleStatus($id = null)
    {
        $this->requireRole([2]);
        if (!$id) {
            $this->redirect('laboratorios');
        }

        $model = $this->model('Laboratorio');
        if ($model->toggleStatus($id)) {
            $_SESSION['flash'] = 'Estado del laboratorio actualizado.';
        } else {
            $_SESSION['error'] = 'No se pudo actualizar el estado del laboratorio.';
        }
        $this->redirect('laboratorios');
    }
}
