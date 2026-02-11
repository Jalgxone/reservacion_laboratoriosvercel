<?php
class InventariosController extends Controller
{
    public function index()
    {
        $this->requireRole([2]);
        $model = $this->model('Inventario');
        $items = $model->getAll();
        $this->view('inventarios/index', ['items' => $items]);
    }

    public function create()
    {
        $this->requireRole([2]);

        $labModel = $this->model('Laboratorio');
        $labs = $labModel->getAll();
        $catModel = $this->model('Recurso');
        $cats = $catModel->getAll();
        $this->view('inventarios/create', ['labs' => $labs, 'cats' => $cats]);
    }

    public function store()
    {
        $this->requireRole([2]);

        $data = [
            'id_laboratorio' => $_POST['id_laboratorio'] ?? null,
            'id_categoria' => $_POST['id_categoria'] ?? null,
            'marca_modelo' => $_POST['marca_modelo'] ?? null,
            'estado_operativo' => $_POST['estado_operativo'] ?? 'Operativo',
        ];

        require_once __DIR__ . '/../../core/Validator.php';
        $rules = [
            'id_laboratorio' => 'required|int',
            'id_categoria' => 'required|int',
            'marca_modelo' => 'required|minlen:5|proper_brand_format'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $labModel = $this->model('Laboratorio');
            $labs = $labModel->getAll();
            $catModel = $this->model('Recurso');
            $cats = $catModel->getAll();
            $this->view('inventarios/create', ['errors' => $errors, 'old' => $data, 'labs' => $labs, 'cats' => $cats]);
            return;
        }

        $model = $this->model('Inventario');
        try {
            $model->create($data);
            $_SESSION['flash'] = 'Equipo agregado al inventario.';
            $this->redirect('inventarios');
        } catch (Exception $e) {
            error_log('InventariosController::store error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al agregar equipo: ' . $e->getMessage();
            $labModel = $this->model('Laboratorio');
            $labs = $labModel->getAll();
            $catModel = $this->model('Recurso');
            $cats = $catModel->getAll();
            $this->view('inventarios/create', ['errors' => [$e->getMessage()], 'old' => $data, 'labs' => $labs, 'cats' => $cats]);
        }
    }

    public function edit($id = null)
    {
        $this->requireRole([2]);

        $model = $this->model('Inventario');
        $item = $model->getById($id);
        if (!$item) {
            $this->redirect('inventarios');
        }
        $labModel = $this->model('Laboratorio');
        $labs = $labModel->getAll();
        $catModel = $this->model('Recurso');
        $cats = $catModel->getAll();
        $this->view('inventarios/edit', ['item' => $item, 'labs' => $labs, 'cats' => $cats]);
    }

    public function update($id = null)
    {
        $this->requireRole([2]);

        $data = [
            'id_laboratorio' => $_POST['id_laboratorio'] ?? null,
            'id_categoria' => $_POST['id_categoria'] ?? null,
            'marca_modelo' => $_POST['marca_modelo'] ?? null,
            'estado_operativo' => $_POST['estado_operativo'] ?? 'Operativo',
        ];

        require_once __DIR__ . '/../../core/Validator.php';
        $rules = [
            'id_laboratorio' => 'required|int',
            'id_categoria' => 'required|int',
            'marca_modelo' => 'required|minlen:5|proper_brand_format'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $model = $this->model('Inventario');
            $item = $model->getById($id);
            $labModel = $this->model('Laboratorio');
            $labs = $labModel->getAll();
            $catModel = $this->model('Recurso');
            $cats = $catModel->getAll();
            $this->view('inventarios/edit', ['errors' => $errors, 'item' => $item, 'labs' => $labs, 'cats' => $cats]);
            return;
        }

        $model = $this->model('Inventario');
        $item = $model->getById($id);

        try {
            if ($item['id_laboratorio'] != $data['id_laboratorio'] && $model->hasActiveReservations($id)) {
                throw new Exception("No se puede mover el equipo de laboratorio porque el origen tiene reservas activas.");
            }

            if ($data['estado_operativo'] === 'Baja' && $model->hasActiveReservations($id)) {
                throw new Exception("No se puede dar de baja el equipo porque el laboratorio tiene reservas activas.");
            }

            if ($data['estado_operativo'] === 'Baja') {
                $data['esta_activo'] = 0;
            }

            $model->update($id, $data);
            
            $_SESSION['flash'] = 'Equipo actualizado.';
            $this->redirect('inventarios');
        } catch (Exception $e) {
            error_log('InventariosController::update error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al actualizar equipo: ' . $e->getMessage();
            $labModel = $this->model('Laboratorio');
            $labs = $labModel->getAll();
            $catModel = $this->model('Recurso');
            $cats = $catModel->getAll();
            $this->view('inventarios/edit', ['errors' => [$e->getMessage()], 'item' => $item, 'labs' => $labs, 'cats' => $cats]);
        }
    }

    public function delete($id = null)
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('auth');
        }

        $model = $this->model('Inventario');
        try {
            $model->delete($id);
            $_SESSION['flash'] = 'Equipo eliminado.';
        } catch (Exception $e) {
            error_log('InventariosController::delete error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al eliminar equipo.';
        }
        $this->redirect('inventarios');
    }

    public function toggleStatus($id = null)
    {
        $this->requireRole([2]);
        if (!$id) {
            $this->redirect('inventarios');
        }

        $model = $this->model('Inventario');
        try {
            if ($model->toggleStatus($id)) {
                $_SESSION['flash'] = 'Estado del equipo actualizado.';
            } else {
                $_SESSION['error'] = 'No se pudo actualizar el estado del equipo.';
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        $this->redirect('inventarios');
    }
}
