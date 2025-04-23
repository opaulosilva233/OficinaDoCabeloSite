<?php
// Configurações para evitar saída indesejada
ini_set('display_errors', 0); // Desativa a exibição de erros na saída
ini_set('display_startup_errors', 0);
error_reporting(0); // Desativa relatórios de erro (apenas para produção)

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Inclui o arquivo de conexão com o banco de dados
require_once 'db.php';

// Função para retornar uma resposta JSON e encerrar o script
function sendResponse($success, $data = [], $message = '') {
    echo json_encode([
        'success' => $success,
        'slots' => $data,
        'message' => $message
    ]);
    exit;
}

// Validação dos parâmetros
if (!isset($_GET['barber']) || !isset($_GET['date'])) {
    sendResponse(false, [], 'Parâmetros barbeiro e data são obrigatórios');
}

// Obtém os parâmetros e sanitiza
$barber = htmlspecialchars($_GET['barber'], ENT_QUOTES, 'UTF-8');
$date = htmlspecialchars($_GET['date'], ENT_QUOTES, 'UTF-8');

// Validação adicional dos parâmetros
if (empty($barber) || empty($date)) {
    sendResponse(false, [], 'Barbeiro ou data inválidos');
}

// Verifica se a conexão com o banco de dados está disponível
if (!isset($pdo) || !($pdo instanceof PDO)) {
    sendResponse(false, [], 'Erro de conexão com o banco de dados');
}

// Lógica para buscar horários disponíveis
$workingHours = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00'];

try {
    // Buscar horários já agendados para o barbeiro na data
    $stmt = $pdo->prepare('SELECT horario_marcacao FROM marcacoes WHERE barbeiro = :barbeiro AND data_marcacao = :data_marcacao AND estado != "cancelada"');
    $stmt->execute(['barbeiro' => $barber, 'data_marcacao' => $date]);
    $bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Filtrar horários disponíveis
    $slots = array_diff($workingHours, $bookedTimes);

    // Ordenar os horários
    sort($slots);

    if (empty($slots)) {
        sendResponse(false, [], 'Nenhum horário disponível para esta data');
    } else {
        sendResponse(true, $slots);
    }
} catch (Exception $e) {
    sendResponse(false, [], 'Erro ao buscar horários: ' . $e->getMessage());
}
?>