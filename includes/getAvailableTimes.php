<?php
// Incluir o arquivo de conexão com o banco de dados
include('./db.php'); // Caminho atualizado com './'

// Verificar se os parâmetros 'barber' e 'date' foram enviados via GET
if (isset($_GET['barber']) && isset($_GET['date'])) {
    $barber = $_GET['barber']; // Obter o barbeiro selecionado
    $date = $_GET['date']; // Obter a data selecionada

    // Log para verificar os parâmetros recebidos
    error_log("Parâmetros recebidos: barber=$barber, date=$date");

    // Conversão da data do formato "DD-MM-YYYY" para "YYYY-MM-DD"
    $dateParts = explode('-', $date); // Divide a data em partes
    if (count($dateParts) == 3) {
        // Reorganiza a data para o formato "YYYY-MM-DD"
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
    } else {
        // Caso o formato de data seja inválido, retorna erro em JSON
        echo json_encode(['success' => false, 'message' => 'O formato da data é inválido.']); // Alteração para português de Portugal
        exit;
    }

    // Consulta SQL para obter os horários já marcados para o barbeiro na data selecionada
    $sql = "SELECT horario_marcacao FROM marcacoes
            WHERE barbeiro = :barber AND data_marcacao = :date AND estado = 'marcada'"; // Filtra as marcações já feitas
    $stmt = $pdo->prepare($sql); // Prepara a consulta
    $stmt->bindParam(':barber', $barber); // Vincula o parâmetro 'barber'
    $stmt->bindParam(':date', $formattedDate); // Vincula o parâmetro 'date'
    $stmt->execute(); // Executa a consulta

    // Obtém os horários que já estão marcados para o barbeiro na data
    $bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Definir os horários disponíveis (das 9h às 18h, com intervalos de 30 minutos)
    $availableTimes = [];
    for ($hour = 9; $hour <= 18; $hour++) {
        // Adiciona os horários de cada meia hora
        $availableTimes[] = sprintf('%02d:00', $hour); // Horário completo (ex: 09:00)
        $availableTimes[] = sprintf('%02d:30', $hour); // Horário de meia hora (ex: 09:30)
    }

    // Remover os horários que já estão marcados da lista de horários disponíveis
    $availableTimes = array_diff($availableTimes, $bookedTimes);

    // Log para verificar os horários disponíveis após a remoção dos já marcados
    error_log("Horários disponíveis: " . implode(', ', $availableTimes));

    // Retornar os horários disponíveis em formato JSON
    echo json_encode(['success' => true, 'slots' => $availableTimes]);
} else {
    // Caso os parâmetros 'barber' ou 'date' não sejam enviados, retorna um erro
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos.']); // Mensagem em português de Portugal
}
?>