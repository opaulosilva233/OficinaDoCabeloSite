<?php
require_once 'db.php';  // Inclua o seu arquivo de conexão com o banco de dados

if (isset($_GET['barber']) && isset($_GET['date'])) {
    $barber = $_GET['barber'];  // O barbeiro selecionado
    $date = $_GET['date'];      // A data selecionada

    // Consulta para verificar horários ocupados na tabela "marcacoes"
    $sql = "SELECT horario_marcacao FROM marcacoes WHERE barbeiro = :barber AND data_marcacao = :date AND estado = 'marcada'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':barber' => $barber, ':date' => $date]);
    $ocupados = $stmt->fetchAll(PDO::FETCH_COLUMN);  // Retorna todos os horários ocupados para essa data e barbeiro

    // Horários disponíveis
    $timeSlots = [];
    $startHour = "10:00";
    $endHour = "17:00";
    $interval = 30;  // Intervalo de 30 minutos

    // Gera os horários de 30 em 30 minutos
    $currentTime = strtotime($startHour);
    $endTime = strtotime($endHour);

    while ($currentTime <= $endTime) {
        $time = date("H:i", $currentTime);  // Hora formatada
        if (!in_array($time, $ocupados)) {  // Se não estiver ocupado
            $timeSlots[] = $time;
        }
        $currentTime = strtotime("+$interval minutes", $currentTime);  // Avança o tempo em 30 minutos
    }

    // Retorna os horários disponíveis em formato JSON
    header('Content-Type: application/json');
    echo json_encode(['slots' => $timeSlots]);
    exit;
}
?>
