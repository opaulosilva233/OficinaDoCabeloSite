<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../Database.php';

$barber = isset($_GET['barber']) ? trim($_GET['barber']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : '';

if (empty($barber) || empty($date)) {
    echo json_encode(['success' => false, 'message' => 'Barbeiro ou data não fornecidos']);
    exit;
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['success' => false, 'message' => 'Formato de data inválido']);
    exit;
}

$db = Database::getInstance()->getConnection();

$allSlots = [
    '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
    '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
    '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
    '18:00', '18:30', '19:00'
];

try {
    $stmt = $db->prepare("SELECT TIME_FORMAT(horario_marcacao, '%H:%i') AS horario_marcacao 
                           FROM marcacoes 
                           WHERE TRIM(barbeiro) = ? AND data_marcacao = ? AND estado = 'marcada'");
    $stmt->execute([$barber, $date]);
    $bookedSlots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $availableSlots = array_diff($allSlots, $bookedSlots);
    sort($availableSlots);

    if (empty($availableSlots)) {
        echo json_encode(['success' => false, 'message' => 'Nenhum horário disponível']);
    } else {
        echo json_encode(['success' => true, 'slots' => array_values($availableSlots)]);
    }
} catch (PDOException $e) {
    error_log("Erro PDO: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar horários']);
}
?>