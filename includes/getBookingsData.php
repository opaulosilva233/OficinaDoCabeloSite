<?php
// Incluir o arquivo de conexão com o banco de dados
include('db.php');

// Consulta SQL para obter o número de marcações por dia da semana (segunda a sábado) na semana atual
$sql = "SELECT DAYOFWEEK(data_marcacao) AS day_of_week, COUNT(*) AS booking_count 
        FROM marcacoes 
        WHERE WEEK(data_marcacao) = WEEK(CURDATE()) 
        GROUP BY DAYOFWEEK(data_marcacao)";

// Executa a consulta SQL
$stmt = $pdo->query($sql);

// Array para armazenar os dados de marcações por dia da semana
$data = [];

// Inicializa os dias da semana com valor 0 (isso garante que todos os dias da semana serão representados)
$weekDays = [
    1 => 'Domingo',
    2 => 'Segunda-feira',
    3 => 'Terça-feira',
    4 => 'Quarta-feira',
    5 => 'Quinta-feira',
    6 => 'Sexta-feira',
    7 => 'Sábado'
];

// Inicializa o array de dados com 0 marcações para cada dia da semana
foreach ($weekDays as $dayNumber => $dayName) {
    $data[$dayNumber] = 0; // Marcações inicializadas em 0
}

// Preenche o array de dados com o número de marcações para cada dia da semana, ignorando o domingo
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $dayOfWeek = $row['day_of_week']; // Obtém o número do dia da semana
    $bookingCount = $row['booking_count']; // Obtém a quantidade de marcações para o dia
    if ($dayOfWeek != 1) { // Ignora o domingo (DAYOFWEEK = 1)
        $data[$dayOfWeek] = $bookingCount; // Atualiza o número de marcações para o dia específico
    }
}

// Define os dias da semana para a exibição
$days = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

// Recria o array de dados, agora gerando números aleatórios entre 0 e 20 (para fins de exemplo)
$data = [];
foreach ($days as $day) {
    $data[] = [
        'day_of_week' => $day, // Nome do dia da semana
        'booking_count' => rand(0, 20)  // Gera um número aleatório de marcações entre 0 e 20
    ];
}

// Define o tipo de resposta como JSON
header('Content-Type: application/json');

// Retorna os dados como JSON
echo json_encode($data);
?>
