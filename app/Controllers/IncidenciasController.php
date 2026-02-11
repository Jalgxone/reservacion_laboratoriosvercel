<?php
class IncidenciasController extends Controller
{
    public function index()
    {
        $this->requireRole([2]);
        $model = $this->model('Incidencia');
        $incidencias = $model->getAll();
        $invModel = $this->model('Inventario');
        $equipos = $invModel ? $invModel->getAll() : [];
        $this->view('incidencias/index', ['incidencias' => $incidencias, 'equipos' => $equipos]);
    }

    public function create()
    {
        $this->requireRole([]);

        $invModel = $this->model('Inventario');
        $equipos = $invModel->getAll();
        $this->view('incidencias/create', ['equipos' => $equipos]);
    }

    public function store()
    {
        $this->requireRole([]);

        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'id_equipo' => $_POST['id_equipo'] ?? null,
            'id_usuario_reporta' => $_SESSION['user']['id_usuario'] ?? $_SESSION['user']['id'] ?? null,
            'descripcion_problema' => trim($_POST['descripcion_problema'] ?? ''),
            'nivel_gravedad' => $_POST['gravedad'] ?? 'media',
            'resuelto' => isset($_POST['resuelto']) ? 1 : 0,
        ];

        $rules = [
            'id_equipo' => 'required|int|exists:inventario,id_equipo',
            'descripcion_problema' => 'required|minlen:10|maxlen:1000',
            'nivel_gravedad' => 'required|in_list:baja,media,alta'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $invModel = $this->model('Inventario');
            $equipos = $invModel ? $invModel->getAll() : [];
            $this->view('incidencias/create', ['errors' => $errors, 'equipos' => $equipos, 'old' => $data]);
            return;
        }

        $model = $this->model('Incidencia');
        try {
            $model->create($data);
            $_SESSION['flash'] = 'Incidencia reportada.';
            $this->redirect('incidencias');
        } catch (Exception $e) {
            error_log('IncidenciasController::store error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al reportar incidencia: ' . $e->getMessage();
            $this->view('incidencias/create', ['errors' => [$e->getMessage()]]);
        }
    }

    public function edit($id = null)
    {
        $this->requireRole([2]);

        $model = $this->model('Incidencia');
        $inc = $model->getById($id);
        if (!$inc) {
            $this->redirect('incidencias');
        }
        $invModel = $this->model('Inventario');
        $equipos = $invModel->getAll();
        $this->view('incidencias/edit', ['incidencia' => $inc, 'equipos' => $equipos]);
    }

    public function update($id = null)
    {
        $this->requireRole([2]);

        require_once __DIR__ . '/../../core/Validator.php';

        $data = [
            'id_equipo' => $_POST['id_equipo'] ?? null,
            'descripcion_problema' => trim($_POST['descripcion_problema'] ?? ''),
            'nivel_gravedad' => $_POST['gravedad'] ?? 'media',
            'resuelto' => isset($_POST['resuelto']) ? 1 : 0,
        ];

        $rules = [
            'id_equipo' => 'required|int',
            'descripcion_problema' => 'required|minlen:5'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $inc = $this->model('Incidencia')->getById($id);
            $invModel = $this->model('Inventario');
            $equipos = $invModel ? $invModel->getAll() : [];
            $this->view('incidencias/edit', ['errors' => $errors, 'incidencia' => array_merge($inc ?: [], $data), 'equipos' => $equipos]);
            return;
        }

        $model = $this->model('Incidencia');
        try {
            $model->update($id, $data);
            $_SESSION['flash'] = 'Incidencia actualizada.';
            $this->redirect('incidencias');
        } catch (Exception $e) {
            error_log('IncidenciasController::update error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al actualizar incidencia: ' . $e->getMessage();
            $inc = $model->getById($id);
            $this->view('incidencias/edit', ['errors' => [$e->getMessage()], 'incidencia' => $inc]);
        }
    }

    public function delete($id = null)
    {
        $this->requireRole([2]);

        $model = $this->model('Incidencia');
        try {
            $model->delete($id);
            $_SESSION['flash'] = 'Incidencia eliminada.';
        } catch (Exception $e) {
            error_log('IncidenciasController::delete error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al eliminar incidencia.';
        }
        $this->redirect('incidencias');
    }
}
