<?php
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../CSRF.php';

class AppointmentController {
    private $auth;
    private $appointmentModel;

    public function __construct() {
        $this->auth = new Auth();
        $this->auth->requireLogin(); // Protect all appointment routes
        $this->appointmentModel = new Appointment();
    }

    public function dashboard() {
        try {
            $daily_summary = $this->appointmentModel->getSummary('daily');
            $weekly_summary = $this->appointmentModel->getSummary('weekly');
            $monthly_summary = $this->appointmentModel->getSummary('monthly');
            
            // Pass data to view
            require_once __DIR__ . '/../../views/dashboard.php';
        } catch (Exception $e) {
            error_log("Dashboard Error: " . $e->getMessage());
            die("Erro ao carregar dashboard.");
        }
    }

    public function agenda() {
        $barberName = $_GET['barber'] ?? 'Bruno Martins';
        
        // Handle Status Update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             if (!isset($_POST['csrf_token']) || !CSRF::verifyToken($_POST['csrf_token'])) {
                 $error = "Erro de segurança (CSRF).";
             } else {
                $id = $_POST['id'];
                $novo_estado = $_POST['estado'];
                
                if ($this->appointmentModel->updateStatus($id, $novo_estado)) {
                    header("Location: index.php?route=agenda&barber=" . urlencode($barberName));
                    exit();
                } else {
                    $error = "Erro ao atualizar estado.";
                }
             }
        }

        $marcacoes_futuras = $this->appointmentModel->getFutureByBarber($barberName);
        $marcacoes_passadas_pendentes = $this->appointmentModel->getPastPendingByBarber($barberName);
        $cssFile = 'assets/css/barbeiro1.css'; // Relative to root now

        require_once __DIR__ . '/../../views/barbeiro_agenda.php';
    }
    
    public function all() {
        // Logic for todasMarcacoes.php would go here
        require_once __DIR__ . '/../../views/todasMarcacoes.php';
    }
}
?>
