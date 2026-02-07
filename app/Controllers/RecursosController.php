<?php
class RecursosController extends Controller
{
    public function index()
    {
        $this->requireRole([2]);
        $model = $this->model('Recurso');
        $recursos = $model->getAll();
        $this->view('recursos/index', ['recursos' => $recursos]);
    }

    public function create()
    {
        $this->requireRole([2]);
        $this->view('recursos/create');
    }

    public function store()
    {
        $this->requireRole([2]);

        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'nombre_categoria' => trim($_POST['nombre_categoria'] ?? ''),
            'requiere_mantenimiento_mensual' => isset($_POST['requiere_mantenimiento_mensual']) ? 1 : 0,
        ];

        $rules = [
            'nombre_categoria' => 'required|minlen:3|maxlen:255'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $this->view('recursos/create', ['errors' => $errors, 'old' => $data]);
            return;
        }

        $model = $this->model('Recurso');
        try {
            $model->create($data);
            $_SESSION['flash'] = 'Categoría agregada.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=recursos');
            exit;
        } catch (Exception $e) {
            error_log('RecursosController::store error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al agregar categoría: ' . $e->getMessage();
            $this->view('recursos/create', ['errors' => [$e->getMessage()]]);
        }
    }

    public function edit($id = null)
    {
        $this->requireRole([2]);
        $model = $this->model('Recurso');
        $rec = $model->getById($id);
        if (!$rec) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=recursos');
            exit;
        }
        $this->view('recursos/edit', ['rec' => $rec]);
    }

    public function update($id = null)
    {
        $this->requireRole([2]);
        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'nombre_categoria' => trim($_POST['nombre_categoria'] ?? ''),
            'requiere_mantenimiento_mensual' => isset($_POST['requiere_mantenimiento_mensual']) ? 1 : 0,
        ];

        $rules = [
            'nombre_categoria' => 'required|minlen:3|maxlen:255'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $rec = $this->model('Recurso')->getById($id);
            $this->view('recursos/edit', ['errors' => $errors, 'rec' => array_merge($rec ?: [], $data), 'old' => $data]);
            return;
        }

        $model = $this->model('Recurso');
        try {
            $model->update($id, $data);
            $_SESSION['flash'] = 'Categoría actualizada.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=recursos');
            exit;
        } catch (Exception $e) {
            error_log('RecursosController::update error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al actualizar categoría: ' . $e->getMessage();
            $rec = $model->getById($id);
            $this->view('recursos/edit', ['errors' => [$e->getMessage()], 'rec' => $rec]);
        }
    }

    public function delete($id = null)
    {
        $this->requireRole([2]);
        $model = $this->model('Recurso');
        try {
            $model->delete($id);
            $_SESSION['flash'] = 'Categoría eliminada.';
        } catch (Exception $e) {
            error_log('RecursosController::delete error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al eliminar categoría.';
        }
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=recursos');
        exit;
    }
}
