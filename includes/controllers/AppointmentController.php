<?php
require_once __DIR__ . '/../models/Appointment.php';
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../CSRF.php';

class AppointmentController {
    private $auth;
    private $appointmentModel;

    public function __construct() {
        $this->auth = new Auth();
        // Removed global requireLogin() to allow public access to booking routes
        $this->appointmentModel = new Appointment();
    }

    public function dashboard() {
        $this->auth->requireLogin();
        try {
            $daily_summary = $this->appointmentModel->getSummary('daily');
            $weekly_summary = $this->appointmentModel->getSummary('weekly');
            $monthly_summary = $this->appointmentModel->getSummary('monthly');
            $next_appointments = $this->appointmentModel->getNextAppointments(5);
            
            // Pass data to view
            require_once __DIR__ . '/../../views/dashboard.php';
        } catch (Exception $e) {
            error_log("Dashboard Error: " . $e->getMessage());
            die("Erro ao carregar dashboard.");
        }
    }

    public function agenda() {
        $this->auth->requireLogin();
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
        $this->auth->requireLogin();
        // Logic for todasMarcacoes.php would go here
        require_once __DIR__ . '/../../views/todasMarcacoes.php';
    }

    public function getChartData() {
        header('Content-Type: application/json');
        try {
            $period = $_GET['period'] ?? 'weekly';
            $data = $this->appointmentModel->getChartStats($period);
            echo json_encode($data);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function getSlots() {
        header('Content-Type: application/json; charset=UTF-8');
        $barber = isset($_GET['barber']) ? trim($_GET['barber']) : '';
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';

        if (empty($barber) || empty($date)) {
            echo json_encode(['success' => false, 'message' => 'Barbeiro ou data não fornecidos']);
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            echo json_encode(['success' => false, 'message' => 'Formato de data inválido']);
            return;
        }

        try {
            $bookedSlots = $this->appointmentModel->getBookedSlots($barber, $date);
            $allSlots = [
                '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
                '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
                '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
                '18:00', '18:30', '19:00'
            ];

            $availableSlots = array_values(array_diff($allSlots, $bookedSlots));
            sort($availableSlots);

            if (empty($availableSlots)) {
                echo json_encode(['success' => false, 'message' => 'Nenhum horário disponível']);
            } else {
                echo json_encode(['success' => true, 'slots' => $availableSlots]);
            }
        } catch (Exception $e) {
            error_log("Erro ao buscar horários: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro interno ao buscar horários.']);
        }
    }

    public function getBusyDays() {
        header('Content-Type: application/json; charset=UTF-8');
        $barber = isset($_GET['barber']) ? trim($_GET['barber']) : '';
        $month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

        if (empty($barber)) {
            echo json_encode(['success' => false, 'message' => 'Barbeiro não fornecido']);
            return;
        }

        try {
            $busyDays = $this->appointmentModel->getFullyBookedDays($barber, $month, $year);
            echo json_encode(['success' => true, 'busyDays' => $busyDays]);
        } catch (Exception $e) {
            error_log("Erro ao buscar dias ocupados: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro interno ao buscar dias ocupados.']);
        }
    }

    public function book() {
        header('Content-Type: application/json; charset=UTF-8');
        $response = ['success' => false, 'message' => ''];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
            return;
        }

        // CSRF Check
        if (!isset($_POST['csrf_token']) || !CSRF::verifyToken($_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'message' => 'Erro de segurança (CSRF). Recarregue a página.']);
            return;
        }

        $service = $_POST['service'] ?? '';
        $barber = $_POST['barber'] ?? '';
        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $observations = $_POST['observations'] ?? '';

        if (empty($service) || empty($barber) || empty($date) || empty($time) || empty($name) || empty($phone) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos']);
            return;
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            echo json_encode(['success' => false, 'message' => 'Formato de data inválido.']);
            return;
        }

        try {
            if ($this->appointmentModel->isSlotTaken($barber, $date, $time)) {
                echo json_encode(['success' => false, 'message' => 'O horário selecionado já não está disponível']);
                return;
            }

            $data = [
                'service' => $service,
                'barber' => $barber,
                'date' => $date,
                'time' => $time,
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'observations' => $observations
            ];

            if ($this->appointmentModel->create($data)) {
                // Tenta enviar o email (sem bloquear se falhar)
                require_once __DIR__ . '/../Mailer.php';
                Mailer::sendConfirmation($email, $name, $data);

                echo json_encode(['success' => true, 'message' => 'Marcação salva com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao salvar a marcação.']);
            }
        } catch (Exception $e) {
            error_log("Booking Error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
        }
    }
    public function getAppointments() {
        $this->auth->requireLogin();
        header('Content-Type: application/json');

        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $search = $_GET['search'] ?? '';
            
            $filters = [
                'barber' => $_GET['barber'] ?? '',
                'status' => $_GET['status'] ?? '',
                'pending_action' => $_GET['pending_action'] ?? '',
                'date_start' => $_GET['date_start'] ?? '',
                'date_end' => $_GET['date_end'] ?? ''
            ];

            $result = $this->appointmentModel->getAll($page, $limit, $search, $filters);
            
            echo json_encode(['success' => true, 'data' => $result['data'], 'pagination' => [
                'total' => $result['total'],
                'pages' => $result['totalPages'],
                'current' => $result['page'],
                'limit' => $result['limit']
            ]]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    public function updateStatusAPI() {
        $this->auth->requireLogin();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
             echo json_encode(['success' => false, 'message' => 'Method not allowed']);
             exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id']) || !isset($input['status'])) {
            echo json_encode(['success' => false, 'message' => 'ID e Estado são obrigatórios']);
            exit;
        }

        try {
            if ($this->appointmentModel->updateStatus($input['id'], $input['status'])) {
                 echo json_encode(['success' => true, 'message' => 'Estado atualizado com sucesso']);
            } else {
                 echo json_encode(['success' => false, 'message' => 'Erro ao atualizar estado']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function getDetails() {
        $this->auth->requireLogin();
        header('Content-Type: application/json');

        try {
            if (!isset($_GET['id'])) {
                throw new Exception("ID not provided");
            }
            
            $id = (int)$_GET['id'];
            
            $appointment = $this->appointmentModel->getById($id);
            if (!$appointment) {
                throw new Exception("Marcação não encontrada");
            }

            $history = $this->appointmentModel->getHistory(
                $appointment['nome_utilizador'], 
                $appointment['telefone_utilizador'], 
                $id
            );

            echo json_encode(['success' => true, 'data' => $appointment, 'history' => $history]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
?>
