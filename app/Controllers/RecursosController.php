<?php
class RecursosController extends Controller
{
    private $allowedCategories = [
        'Computadora', 'Laptop', 'Proyector', 'Impresora', 
        'Monitor', 'Servidor', 'Equipo de Red', 'Periférico', 
        'Accesorio', 'Cámara', 'Equipo de Sonido'
    ];

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
        $this->view('recursos/create', ['categories' => $this->allowedCategories]);
    }

    public function store()
    {
        $this->requireRole([2]);

        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'nombre_categoria' => trim($_POST['nombre_categoria'] ?? ''),
            'requiere_mantenimiento_mensual' => isset($_POST['requiere_mantenimiento_mensual']) ? 1 : 0,
            'observacion' => trim($_POST['observacion'] ?? ''),
        ];

        $rules = [
            'nombre_categoria' => 'required|in_list:' . implode(',', $this->allowedCategories) . '|unique:categorias_equipo,nombre_categoria',
            'observacion' => 'maxlen:500'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $this->view('recursos/create', ['errors' => $errors, 'old' => $data, 'categories' => $this->allowedCategories]);
            return;
        }

        $model = $this->model('Recurso');
        try {
            $model->create($data);
            $_SESSION['flash'] = 'Categoría agregada.';
            $this->redirect('recursos');
        } catch (Exception $e) {
            error_log('RecursosController::store error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al agregar categoría: ' . $e->getMessage();
            $this->view('recursos/create', ['errors' => [$e->getMessage()], 'old' => $data]);
        }
    }

    public function edit($id = null)
    {
        $this->requireRole([2]);
        $model = $this->model('Recurso');
        $rec = $model->getById($id);
        if (!$rec) {
            $this->redirect('recursos');
        }
        $this->view('recursos/edit', ['rec' => $rec, 'categories' => $this->allowedCategories]);
    }

    public function update($id = null)
    {
        $this->requireRole([2]);
        if (!$id) {
            $this->redirect('recursos');
        }

        require_once __DIR__ . '/../../core/Validator.php';

        $model = $this->model('Recurso');
        $rec = $model->getById($id);
        if (!$rec) {
            $_SESSION['error'] = 'La categoría no existe.';
            $this->redirect('recursos');
            return;
        }

        $data = [
            'nombre_categoria' => trim($_POST['nombre_categoria'] ?? ''),
            'requiere_mantenimiento_mensual' => isset($_POST['requiere_mantenimiento_mensual']) ? 1 : 0,
            'observacion' => trim($_POST['observacion'] ?? ''),
        ];

        $rules = [
            'nombre_categoria' => "required|in_list:" . implode(',', $this->allowedCategories) . "|unique:categorias_equipo,nombre_categoria,id_categoria,$id",
            'observacion' => 'maxlen:500'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $this->view('recursos/edit', ['errors' => $errors, 'rec' => array_merge($rec ?: [], $data), 'old' => $data, 'categories' => $this->allowedCategories]);
            return;
        }

        try {
            $model->update($id, $data);
            $_SESSION['flash'] = 'Categoría actualizada.';
            $this->redirect('recursos');
        } catch (Exception $e) {
            error_log('RecursosController::update error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al actualizar categoría: ' . $e->getMessage();
            $this->view('recursos/edit', ['errors' => [$e->getMessage()], 'rec' => $rec, 'categories' => $this->allowedCategories]);
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
        $this->redirect('recursos');
    }

    public function toggleStatus($id = null)
    {
        $this->requireRole([2]);
        if (!$id) {
            $this->redirect('recursos');
        }

        $model = $this->model('Recurso');
        if ($model->toggleStatus($id)) {
            $_SESSION['flash'] = 'Estado de la categoría actualizado.';
        } else {
            $_SESSION['error'] = 'No se pudo actualizar el estado de la categoría.';
        }
        $this->redirect('recursos');
    }
}
