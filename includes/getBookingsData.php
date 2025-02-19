<?php
// Incluir o arquivo de conexão com o banco de dados
include('db.php'); // Adicionado ponto e vírgula

try {
    // Data atual para pegar os dias da semana
    $startOfWeek = date('Y-m-d', strtotime('monday this week')); // Segunda-feira
    $endOfWeek = date('Y-m-d', strtotime('saturday this week')); // Sábado (Corrigido)

    // Consulta SQL para contar o número de marcações por dia
    $query = "
    SELECT
        DAYOFWEEK(data_marcacao) AS day_of_week, 
        COUNT(*) AS booking_count
    FROM marcacoes
    WHERE data_marcacao BETWEEN :startOfWeek AND :endOfWeek
    GROUP BY DAYOFWEEK(data_marcacao)
    ORDER BY day_of_week ASC
";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':startOfWeek', $startOfWeek);
    $stmt->bindParam(':endOfWeek', $endOfWeek);
    $stmt->execute();

    // Mapeando os resultados para os dias da semana
    $diasSemana = [
        '2' => 'Segunda', '3' => 'Terça', '4' => 'Quarta',
        '5' => 'Quinta', '6' => 'Sexta', '7' => 'Sábado'
    ];

    // Inicializa os contadores para os dias de Segunda a Sábado
    $bookings = array_fill_keys(array_keys($diasSemana), 0);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $day = $row['day_of_week'];
        if (isset($bookings[$day])) {
            $bookings[$day] = (int)$row['booking_count']; // Preenche o contador do dia
        }
    }

    // Organiza os dados para retorno como JSON
    $result = [];
    foreach ($bookings as $key => $value) {
        $result[] = [
            'day_of_week' => $diasSemana[$key], // Nome do dia
            'booking_count' => $value // Número de marcações
        ];
    }

    // Retorna os dados no formato JSON
    echo json_encode($result);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro na consulta ao banco de dados: ' . $e->getMessage()]);
}
?>
