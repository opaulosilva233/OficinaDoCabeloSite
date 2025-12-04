<?php
// Garantir que o PHP use UTF-8
header('Content-Type: application/json; charset=UTF-8');

// Incluir o Model de Appointment
require_once 'models/Appointment.php';

// Garante que não há saída antes do JSON
ob_start();

// Inicializar a resposta
$response = ['success' => false, 'message' => ''];

// Receber os dados do formulário
$service = isset($_POST['service']) ? trim($_POST['service']) : '';
$barber = isset($_POST['barber']) ? trim($_POST['barber']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$time = isset($_POST['time']) ? trim($_POST['time']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

try {
    // Validar os dados
    if (empty($service) || empty($barber) || empty($date) || empty($time) || empty($name) || empty($phone) || empty($email)) {
        $response['message'] = 'Por favor, preencha todos os campos';
        echo json_encode($response);
        exit;
    }

    // Validar o formato da data
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $response['message'] = 'Formato de data inválido. Use o formato YYYY-MM-DD';
        echo json_encode($response);
        exit;
    }

    $appointment = new Appointment();

    // Verificar se o horário ainda está disponível
    if ($appointment->isSlotTaken($barber, $date, $time)) {
        $response['message'] = 'O horário selecionado já não está disponível';
        echo json_encode($response);
        exit;
    }

    // Inserir os dados
    $data = [
        'service' => $service,
        'barber' => $barber,
        'date' => $date,
        'time' => $time,
        'name' => $name,
        'phone' => $phone,
        'email' => $email
    ];

    if ($appointment->create($data)) {
        $response['success'] = true;
        $response['message'] = 'Marcação salva com sucesso!';
    } else {
        $response['message'] = 'Erro ao salvar a marcação.';
    }

} catch (Exception $e) {
    $response['message'] = 'Erro ao salvar a marcação: ' . $e->getMessage();
    error_log("Erro ao salvar a marcação: " . $e->getMessage());
    echo json_encode($response);
    exit;
}

// Limpa qualquer saída indesejada antes do JSON
ob_end_clean();

// Envia a resposta JSON
echo json_encode($response);
exit;
?>