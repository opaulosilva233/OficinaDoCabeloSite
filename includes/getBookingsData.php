<?php
// Conectar ao banco de dados
include('db.php');

// Consulta SQL para obter o número de marcações por dia da semana (segunda a sábado)
$sql = "SELECT DAYOFWEEK(data_marcacao) AS day_of_week, COUNT(*) AS booking_count 
        FROM marcacoes 
        WHERE WEEK(data_marcacao) = WEEK(CURDATE()) 
        GROUP BY DAYOFWEEK(data_marcacao)";

// Executar a consulta
$stmt = $pdo->query($sql);

// Armazenar os resultados em um array
$data = [];

// Inicializa os dias da semana com valor 0 (para garantir que todos os dias da semana sejam representados)
$weekDays = [1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terça-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sábado'];

foreach ($weekDays as $dayNumber => $dayName) {
    $data[$dayNumber] = 0; // Inicializa com 0 as marcações para todos os dias
}

// Preenche os dados com o número de marcações para cada dia da semana
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dayOfWeek = $row['day_of_week'];
    $bookingCount = $row['booking_count'];
    if ($dayOfWeek != 1) { // Ignora o domingo (DAYOFWEEK = 1)
        $data[$dayOfWeek] = $bookingCount;
    }
}

$days = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
$data = [];

foreach ($days as $day) {
    $data[] = [
        'day_of_week' => $day,
        'booking_count' => rand(0, 20)  // Gera um número aleatório entre 0 e 20
    ];
}


// Retorna os dados como JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
