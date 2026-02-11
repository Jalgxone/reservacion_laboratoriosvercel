<?php
class ReservasController extends Controller
{
    public function index()
    {
        $this->requireRole([]);

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

        $start = isset($_POST['fecha_inicio']) ? str_replace('T', ' ', $_POST['fecha_inicio']) : null;
        $end = isset($_POST['fecha_fin']) ? str_replace('T', ' ', $_POST['fecha_fin']) : null;

        $current = $this->currentUser();

        $estadoInicial = 1; // Pendiente por defecto


        $data = [
            'id_usuario' => $current['id'] ?? null,
            'id_laboratorio' => $_POST['id_laboratorio'] ?? null,
            'fecha_inicio' => $start,
            'fecha_fin' => $end,
            'id_estado' => $estadoInicial,
            'motivo_uso' => $_POST['motivo_uso'] ?? null,
        ];

        require_once __DIR__ . '/../../core/Validator.php';
        $rules = [
            'id_usuario' => 'required|int',
            'id_laboratorio' => 'required|int',
            'fecha_inicio' => 'required|datetime|future|business_hours:7:20',
            'fecha_fin' => 'required|datetime|after_field:fecha_inicio|business_hours:7:21',
            'motivo_uso' => 'required|minlen:5'
        ];

        $errors = Validator::validate($data, $rules, [
            'fecha_inicio' => 'fecha de inicio',
            'fecha_fin' => 'fecha de finalización',
            'id_laboratorio' => 'laboratorio',
            'motivo_uso' => 'motivo del uso'
        ]);


        $labModel = $this->model('Laboratorio');
        $lab = $labModel->getById($data['id_laboratorio']);
        if (!$lab || $lab['esta_activo'] == 0) {
            $errors['id_laboratorio'][] = 'El laboratorio seleccionado no está disponible para nuevas reservas.';
        }

        if (!empty($errors)) {
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
            $this->redirect('reservas');
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

        $user = $this->currentUser();
        if ($user['id_rol'] != 2 && $reserva['id_usuario'] != $user['id_usuario']) {
            $_SESSION['flash'] = 'No tienes permiso para editar esta reserva.';
            $this->redirect('reservas');
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

        $user = $this->currentUser();
        if ($user['id_rol'] != 2 && $reserva['id_usuario'] != $user['id_usuario']) {
            $_SESSION['flash'] = 'No tienes permiso para actualizar esta reserva.';
            $this->redirect('reservas');
        }

        $start = isset($_POST['fecha_inicio']) ? str_replace('T', ' ', $_POST['fecha_inicio']) : null;
        $end = isset($_POST['fecha_fin']) ? str_replace('T', ' ', $_POST['fecha_fin']) : null;

        $estadoFinal = $reserva['id_estado'];
        if (isset($user['id_rol']) && $user['id_rol'] == 2 && isset($_POST['id_estado'])) {
            $estadoFinal = $_POST['id_estado'];
        }

        $data = [
            'id_laboratorio' => $_POST['id_laboratorio'] ?? null,
            'fecha_inicio' => $start,
            'fecha_fin' => $end,
            'id_estado' => $estadoFinal,
            'motivo_uso' => $_POST['motivo_uso'] ?? null,
        ];

        require_once __DIR__ . '/../../core/Validator.php';
        $rules = [
            'id_laboratorio' => 'required|int',
            'fecha_inicio' => 'required|datetime|future|business_hours:7:20',
            'fecha_fin' => 'required|datetime|after_field:fecha_inicio|business_hours:7:21',
            'motivo_uso' => 'required|minlen:5'
        ];

        $errors = Validator::validate($data, $rules);


        $labModel = $this->model('Laboratorio');
        $lab = $labModel->getById($data['id_laboratorio']);
        if (!$lab || $lab['esta_activo'] == 0) {
            $errors['id_laboratorio'][] = 'El laboratorio seleccionado no está disponible para reservas.';
        }

        if (!empty($errors)) {
            $labs = $labModel->getAll();
            $estadoModel = $this->model('EstadoReserva');
            $estados = $estadoModel ? $estadoModel->getAll() : [];
            $this->view('reservas/edit', [
                'errors' => $errors, 
                'reserva' => $reserva, 
                'labs' => $labs, 
                'estados' => $estados,
                'old' => $data
            ]);
            return;
        }

        try {
            $model->update($id, $data);
            $_SESSION['flash'] = 'Reserva actualizada.';
            $this->redirect('reservas');
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
        
        $this->redirect('reservas');
    }
}
