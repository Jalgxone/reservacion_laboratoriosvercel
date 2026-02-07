<?php
class ReservasController extends Controller
{
    public function index()
    {
        $this->requireRole([]); // requiere estar autenticado

        $model = $this->model('Reserva');
        $reservas = $model->getAll();
        $this->view('reservas/index', ['reservas' => $reservas]);
    }

    public function create()
    {
        $this->requireRole([]);

        $labModel = $this->model('Laboratorio');
        $labs = $labModel->getAll();
        $estadoModel = $this->model('EstadoReserva');
        $estados = $estadoModel ? $estadoModel->getAll() : [];
        $this->view('reservas/create', ['labs' => $labs, 'estados' => $estados]);
    }

    public function store()
    {
        $this->requireRole([]);

        // convert datetime-local to MySQL datetime
        $start = isset($_POST['fecha_inicio']) ? str_replace('T', ' ', $_POST['fecha_inicio']) : null;
        $end = isset($_POST['fecha_fin']) ? str_replace('T', ' ', $_POST['fecha_fin']) : null;

        $current = $this->currentUser();

        $data = [
            'id_usuario' => $current['id'] ?? null,
            'id_laboratorio' => $_POST['id_laboratorio'] ?? null,
            'fecha_inicio' => $start,
            'fecha_fin' => $end,
            'id_estado' => $_POST['id_estado'] ?? 1,
            'motivo_uso' => $_POST['motivo_uso'] ?? null,
        ];

        require_once __DIR__ . '/../../core/Validator.php';
        $rules = [
            'id_usuario' => 'required|int',
            'id_laboratorio' => 'required|int',
            'fecha_inicio' => 'required|datetime',
            'fecha_fin' => 'required|datetime',
        ];

        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $labModel = $this->model('Laboratorio');
            $labs = $labModel->getAll();
            $estadoModel = $this->model('EstadoReserva');
            $estados = $estadoModel ? $estadoModel->getAll() : [];
            $this->view('reservas/create', ['errors' => $errors, 'labs' => $labs, 'estados' => $estados, 'old' => $data]);
            return;
        }

        $model = $this->model('Reserva');
        try {
            $model->create($data);
            $_SESSION['flash'] = 'Reserva creada.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=reservas');
            exit;
        } catch (Exception $e) {
            error_log('ReservasController::store error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al crear reserva: ' . $e->getMessage();
            $labModel = $this->model('Laboratorio');
            $labs = $labModel->getAll();
            $estadoModel = $this->model('EstadoReserva');
            $estados = $estadoModel ? $estadoModel->getAll() : [];
            $this->view('reservas/create', ['errors' => [$e->getMessage()], 'labs' => $labs, 'estados' => $estados, 'old' => $data]);
        }
    }

    public function edit($id = null)
    {
        $this->requireRole([]);

        $model = $this->model('Reserva');
        $reserva = $model->getById($id);
        if (!$reserva) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=reservas');
            exit;
        }

        // Solo el dueño o un admin pueden editar
        $user = $this->currentUser();
        if ($user['id_rol'] != 2 && $reserva['id_usuario'] != $user['id_usuario']) {
            $_SESSION['flash'] = 'No tienes permiso para editar esta reserva.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=reservas');
            exit;
        }

        $labModel = $this->model('Laboratorio');
        $labs = $labModel->getAll();
        $estadoModel = $this->model('EstadoReserva');
        $estados = $estadoModel ? $estadoModel->getAll() : [];
        $this->view('reservas/edit', ['reserva' => $reserva, 'labs' => $labs, 'estados' => $estados]);
    }

    public function update($id = null)
    {
        $this->requireRole([]);

        $model = $this->model('Reserva');
        $reserva = $model->getById($id);
        if (!$reserva) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=reservas');
            exit;
        }

        // Solo el dueño o un admin pueden actualizar
        $user = $this->currentUser();
        if ($user['id_rol'] != 2 && $reserva['id_usuario'] != $user['id_usuario']) {
            $_SESSION['flash'] = 'No tienes permiso para actualizar esta reserva.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=reservas');
            exit;
        }

        $start = isset($_POST['fecha_inicio']) ? str_replace('T', ' ', $_POST['fecha_inicio']) : null;
        $end = isset($_POST['fecha_fin']) ? str_replace('T', ' ', $_POST['fecha_fin']) : null;

        $data = [
            'id_laboratorio' => $_POST['id_laboratorio'] ?? null,
            'fecha_inicio' => $start,
            'fecha_fin' => $end,
            'id_estado' => $_POST['id_estado'] ?? 1,
            'motivo_uso' => $_POST['motivo_uso'] ?? null,
        ];

        require_once __DIR__ . '/../../core/Validator.php';
        $rules = [
            'id_laboratorio' => 'required|int',
            'fecha_inicio' => 'required|datetime',
            'fecha_fin' => 'required|datetime'
        ];
        $errors = Validator::validate($data, $rules);
        if (!empty($errors)) {
            $labModel = $this->model('Laboratorio');
            $labs = $labModel->getAll();
            $estadoModel = $this->model('EstadoReserva');
            $estados = $estadoModel ? $estadoModel->getAll() : [];
            $this->view('reservas/edit', ['errors' => $errors, 'reserva' => $reserva, 'labs' => $labs, 'estados' => $estados]);
            return;
        }

        try {
            $model->update($id, $data);
            $_SESSION['flash'] = 'Reserva actualizada.';
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=reservas');
            exit;
        } catch (Exception $e) {
            error_log('ReservasController::update error: ' . $e->getMessage());
            $_SESSION['flash'] = 'Error al actualizar reserva: ' . $e->getMessage();
            $reserva = $model->getById($id);
            $labModel = $this->model('Laboratorio');
            $labs = $labModel->getAll();
            $estadoModel = $this->model('EstadoReserva');
            $estados = $estadoModel ? $estadoModel->getAll() : [];
            $this->view('reservas/edit', ['errors' => [$e->getMessage()], 'reserva' => $reserva, 'labs' => $labs, 'estados' => $estados]);
        }
    }

    public function delete($id = null)
    {
        $this->requireRole([]);

        $model = $this->model('Reserva');
        $reserva = $model->getById($id);
        if ($reserva) {
            // Solo el dueño o un admin pueden eliminar
            $user = $this->currentUser();
            if ($user['id_rol'] == 2 || $reserva['id_usuario'] == $user['id_usuario']) {
                try {
                    $model->delete($id);
                    $_SESSION['flash'] = 'Reserva eliminada.';
                } catch (Exception $e) {
                    error_log('ReservasController::delete error: ' . $e->getMessage());
                    $_SESSION['flash'] = 'Error al eliminar reserva.';
                }
            } else {
                $_SESSION['flash'] = 'No tienes permiso para eliminar esta reserva.';
            }
        }
        
        header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=reservas');
        exit;
    }
}
