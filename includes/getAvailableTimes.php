<?php
// Incluir o arquivo de conexão com o banco de dados. Este arquivo estabelece a conexão com a base de dados MySQL.
include('./db.php'); // Caminho atualizado com './'

// Verificar se os parâmetros 'barber' (barbeiro) e 'date' (data) foram enviados através do método GET.
if (isset($_GET['barber']) && isset($_GET['date'])) {
    // Obter o barbeiro selecionado a partir do parâmetro GET 'barber'.
    $barber = $_GET['barber']; 
    // Obter a data selecionada a partir do parâmetro GET 'date'.
    $date = $_GET['date']; 

    // Registar (log) os parâmetros recebidos para fins de depuração.
    error_log("Parâmetros recebidos: barber=$barber, date=$date");

    // Converter a data do formato "DD-MM-YYYY" (formato recebido) para "YYYY-MM-DD" (formato usado no banco de dados).
    $dateParts = explode('-', $date); // Dividir a data em partes usando o hífen como delimitador.
    if (count($dateParts) == 3) {
        // Reorganizar a data para o formato "YYYY-MM-DD".
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
    } else {
        // Caso o formato de data seja inválido, retornar uma mensagem de erro em formato JSON.
        echo json_encode(['success' => false, 'message' => 'O formato da data é inválido.']); // Alteração para português de Portugal
        exit;
    }

    // Preparar a consulta SQL para obter os horários já marcados para o barbeiro e na data selecionada.
    // Esta consulta irá buscar todas as marcações para um dado barbeiro numa data específica que já tenham sido marcadas.
    $sql = "SELECT horario_marcacao FROM marcacoes
            WHERE barbeiro = :barber AND data_marcacao = :date AND estado = 'marcada'"; // Filtrar apenas as marcações com estado 'marcada'.
    // Preparar a consulta SQL usando PDO para evitar SQL injection.
    $stmt = $pdo->prepare($sql); 
    // Vincular o parâmetro 'barber' na consulta SQL ao valor de $barber.
    $stmt->bindParam(':barber', $barber); 
    // Vincular o parâmetro 'date' na consulta SQL ao valor de $formattedDate.
    $stmt->bindParam(':date', $formattedDate); 
    // Executar a consulta preparada.
    $stmt->execute(); 

    // Obter os horários que já estão marcados para o barbeiro na data selecionada.
    // PDO::FETCH_COLUMN, 0 irá retornar um array simples com os horários.
    $bookedTimes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    // Definir os horários disponíveis (das 9h às 18h, com intervalos de 30 minutos)
    $availableTimes = [];
    for ($hour = 9; $hour <= 18; $hour++) {
        // Adiciona os horários de cada meia hora
        $availableTimes[] = sprintf('%02d:00', $hour); // Horário completo (ex: 09:00)
        $availableTimes[] = sprintf('%02d:30', $hour); // Horário de meia hora (ex: 09:30)
    }

    // Remover os horários que já estão marcados da lista de horários disponíveis.
    $availableTimes = array_diff($availableTimes, $bookedTimes);

    // Registo (log) dos horários disponíveis após a remoção dos horários já marcados.
    error_log("Horários disponíveis: " . implode(', ', $availableTimes));

    // Retornar os horários disponíveis em formato JSON para serem processados pelo lado do cliente (JavaScript).
    echo json_encode(['success' => true, 'slots' => $availableTimes]);
} else {
    // Caso os parâmetros 'barber' ou 'date' não sejam enviados, retornar uma mensagem de erro em formato JSON.
    echo json_encode(['success' => false, 'message' => 'Parâmetros inválidos.']); // Mensagem em português de Portugal
}
?>