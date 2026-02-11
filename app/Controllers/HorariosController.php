<?php
class HorariosController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('auth');
        }

        $labModel = $this->model('Laboratorio');
        $laboratorios = $labModel->getAll();

        $labId = $_GET['lab'] ?? ($laboratorios[0]['id_laboratorio'] ?? null);

        $dateParam = $_GET['date'] ?? date('Y-m-d');
        $timestamp = strtotime($dateParam);
        $dayOfWeek = date('w', $timestamp);
        $diff = $dayOfWeek; 
        $sundayTimestamp = strtotime("-{$diff} days", $timestamp);
        
        $weekStart = date('Y-m-d', $sundayTimestamp);
        $weekEnd = date('Y-m-d', strtotime('+6 days', $sundayTimestamp));

        $reservas = [];
        if ($labId) {
            $resModel = $this->model('Reserva');
            $queryEnd = date('Y-m-d', strtotime('+1 day', strtotime($weekEnd)));
            $reservas = $resModel->getSchedule($labId, $weekStart, $queryEnd);
        }

        $prevWeek = date('Y-m-d', strtotime('-1 week', $sundayTimestamp));
        $nextWeek = date('Y-m-d', strtotime('+1 week', $sundayTimestamp));

        $data = [
            'reservas' => $reservas,
            'laboratorios' => $laboratorios,
            'selectedLab' => $labId,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'prevWeek' => $prevWeek,
            'nextWeek' => $nextWeek,
        ];

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return $this->jsonResponse($data);
        }

        $this->view('horarios/index', $data);
    }
}
