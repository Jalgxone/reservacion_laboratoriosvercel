<?php
class HorariosController extends Controller
{
    public function index()
    {
        if (empty($_SESSION['user'])) {
            header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?url=auth');
            exit;
        }

        $labModel = $this->model('Laboratorio');
        $laboratorios = $labModel->getAll();

        // 1. Determine selected Lab
        $labId = $_GET['lab'] ?? ($laboratorios[0]['id_laboratorio'] ?? null);

        // 2. Determine Week Start (Default to this week's Sunday)
        $dateParam = $_GET['date'] ?? date('Y-m-d');
        // Find Sunday of that week
        $timestamp = strtotime($dateParam);
        $dayOfWeek = date('w', $timestamp); // 0 (Sun) - 6 (Sat)
        
        // Adjust to make Sunday(0) the start. Simply subtract current dayOfWeek.
        $diff = $dayOfWeek; 
        $sundayTimestamp = strtotime("-{$diff} days", $timestamp);
        
        $weekStart = date('Y-m-d', $sundayTimestamp);
        $weekEnd = date('Y-m-d', strtotime('+6 days', $sundayTimestamp)); // Saturday

        // 3. Fetch Reservations
        $reservas = [];
        if ($labId) {
            $resModel = $this->model('Reserva');
            // We fetch up to next Monday to include Sunday fully if needed, logic depends on query usage
            // The query uses: fecha_inicio >= start AND fecha_inicio < end.
            // So we need end to be 'next monday' to capture sunday events? 
            // Actually let's use +1 day for the end of range strictly
            $queryEnd = date('Y-m-d', strtotime('+1 day', strtotime($weekEnd)));
            $reservas = $resModel->getSchedule($labId, $weekStart, $queryEnd);
        }

        // 4. Prepare view data
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
