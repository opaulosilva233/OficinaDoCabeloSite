<?php
header('Content-Type: application/json; charset=UTF-8');

// Incluir a conexão com o banco de dados
require_once 'db.php';

// Receber os parâmetros
$barber = isset($_GET['barber']) ? trim($_GET['barber']) : '';
$date = isset($_GET['date']) ? trim($_GET['date']) : '';

if (empty($barber) || empty($date)) {
    echo json_encode(['success' => false, 'message' => 'Barbeiro ou data não fornecidos']);
    exit;
}

// Validar o formato da data (esperado: YYYY-MM-DD, ex.: 2025-04-25)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['success' => false, 'message' => 'Formato de data inválido. Use o formato YYYY-MM-DD']);
    exit;
}

// Log dos parâmetros recebidos
error_log("Parâmetros recebidos: barbeiro=$barber, data=$date");

// Lista de horários disponíveis (baseado no horário de funcionamento)
$allSlots = [
    '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
    '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
    '15:00', '15:30', '16:00', '16:30', '17:00', '17:30',
    '18:00', '18:30', '19:00'
];

// Buscar horários já ocupados na tabela marcacoes
try {
    // Usar TIME_FORMAT para retornar o horário no formato HH:MM
    $stmt = $pdo->prepare("SELECT TIME_FORMAT(horario_marcacao, '%H:%i') AS horario_marcacao 
                           FROM marcacoes 
                           WHERE TRIM(barbeiro) = ? AND data_marcacao = ? AND estado = 'marcada'");
    $stmt->execute([$barber, $date]);
    $bookedSlots = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Log temporário para depuração
    error_log("Horários ocupados para barbeiro=$barber, data=$date: " . json_encode($bookedSlots));

    // Verificar se os horários ocupados estão no formato correto
    if (empty($bookedSlots)) {
        error_log("Nenhum horário ocupado encontrado para barbeiro=$barber, data=$date. Verifique os dados na tabela marcacoes.");
    }

    // Filtrar os horários disponíveis, removendo os já ocupados
    $availableSlots = array_diff($allSlots, $bookedSlots);

    // Log temporário para depuração
    error_log("Horários disponíveis para barbeiro=$barber, data=$date: " . json_encode($availableSlots));

    // Ordenar os horários disponíveis
    sort($availableSlots);

    if (empty($availableSlots)) {
        echo json_encode(['success' => false, 'message' => 'Nenhum horário disponível para esta data']);
    } else {
        echo json_encode(['success' => true, 'slots' => array_values($availableSlots)]);
    }
} catch (PDOException $e) {
    error_log("Erro PDO: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro ao buscar horários: ' . $e->getMessage()]);
}
?>