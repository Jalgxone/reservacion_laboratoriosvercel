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
            'codigo_serial' => $_POST['codigo_serial'] ?? '',
            'id_laboratorio' => $_POST['id_laboratorio'] ?? null,
            'id_categoria' => $_POST['id_categoria'] ?? null,
            'marca_modelo' => $_POST['marca_modelo'] ?? null,
            'estado_operativo' => $_POST['estado_operativo'] ?? 'Operativo',
        ];

        require_once __DIR__ . '/../../core/Validator.php';
        $rules = [
            'codigo_serial' => 'required|minlen:2|unique:inventario,codigo_serial',
            'id_laboratorio' => 'required|int',
            'id_categoria' => 'required|int'
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
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=inventarios');
            exit;
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
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=inventarios');
            exit;
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
            'codigo_serial' => $_POST['codigo_serial'] ?? '',
            'id_laboratorio' => $_POST['id_laboratorio'] ?? null,
            'id_categoria' => $_POST['id_categoria'] ?? null,
            'marca_modelo' => $_POST['marca_modelo'] ?? null,
            'estado_operativo' => $_POST['estado_operativo'] ?? 'Operativo',
        ];

        require_once __DIR__ . '/../../core/Validator.php';
        $rules = [
            'codigo_serial' => 'required|minlen:2',
            'id_laboratorio' => 'required|int',
            'id_categoria' => 'required|int'
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
        try {
            $model->update($id, $data);
            $_SESSION['flash'] = 'Equipo actualizado.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=inventarios');
            exit;
        } catch (Exception $e) {
            error_log('InventariosController::update error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al actualizar equipo: ' . $e->getMessage();
            $item = $model->getById($id);
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
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }

        $model = $this->model('Inventario');
        try {
            $model->delete($id);
            $_SESSION['flash'] = 'Equipo eliminado.';
        } catch (Exception $e) {
            error_log('InventariosController::delete error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al eliminar equipo.';
        }
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=inventarios');
        exit;
    }
}
