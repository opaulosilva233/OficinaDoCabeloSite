<?php
// Incluir o ficheiro de ligação à base de dados
include('./db.php');

try {
    // Verificar o parâmetro 'period'
    $period = isset($_GET['period']) ? $_GET['period'] : 'weekly';
    error_log('Período selecionado: ' . $period);

    if ($period === 'daily') {
        // Dados diários (intervalos de 4 horas)
        $current_date = date('Y-m-d');
        error_log('Data atual para daily: ' . $current_date);
        $query = "
            SELECT 
                CASE 
                    WHEN HOUR(hora_marcacao) BETWEEN 0 AND 3 THEN '00h-04h'
                    WHEN HOUR(hora_marcacao) BETWEEN 4 AND 7 THEN '04h-08h'
                    WHEN HOUR(hora_marcacao) BETWEEN 8 AND 11 THEN '08h-12h'
                    WHEN HOUR(hora_marcacao) BETWEEN 12 AND 15 THEN '12h-16h'
                    WHEN HOUR(hora_marcacao) BETWEEN 16 AND 19 THEN '16h-20h'
                    ELSE '20h-24h'
                END AS time_slot,
                estado,
                COUNT(*) AS booking_count
            FROM marcacoes
            WHERE data_marcacao = :current_date
            GROUP BY time_slot, estado
            ORDER BY FIELD(time_slot, '00h-04h', '04h-08h', '08h-12h', '12h-16h', '16h-20h', '20h-24h')
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':current_date', $current_date);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log('Dados brutos para daily: ' . json_encode($rows));

        $time_slots = ['00h-04h', '04h-08h', '08h-12h', '12h-16h', '16h-20h', '20h-24h'];
        $bookings = array_fill_keys($time_slots, ['marcadas' => 0, 'concluidas' => 0, 'canceladas' => 0]);

        foreach ($rows as $row) {
            $bookings[$row['time_slot']][$row['estado']] = (int)$row['booking_count'];
        }

        $result = [];
        foreach ($bookings as $slot => $counts) {
            $result[] = [
                'time_slot' => $slot,
                'marcadas' => $counts['marcadas'],
                'concluidas' => $counts['concluidas'],
                'canceladas' => $counts['canceladas']
            ];
        }
    } elseif ($period === 'monthly') {
        // Dados mensais (por dia do mês)
        $days_in_month = date('t');
        $current_month = date('m');
        $current_year = date('Y');
        error_log('Mês e ano para monthly: ' . $current_month . '/' . $current_year);
        $query = "
            SELECT 
                DAY(data_marcacao) AS day,
                estado,
                COUNT(*) AS booking_count
            FROM marcacoes
            WHERE MONTH(data_marcacao) = :current_month AND YEAR(data_marcacao) = :current_year
            GROUP BY DAY(data_marcacao), estado
            ORDER BY day ASC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':current_month', $current_month);
        $stmt->bindParam(':current_year', $current_year);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log('Dados brutos para monthly: ' . json_encode($rows));

        $bookings = [];
        for ($day = 1; $day <= $days_in_month; $day++) {
            $bookings[$day] = ['marcadas' => 0, 'concluidas' => 0, 'canceladas' => 0];
        }

        foreach ($rows as $row) {
            $bookings[$row['day']][$row['estado']] = (int)$row['booking_count'];
        }

        $result = [];
        for ($day = 1; $day <= $days_in_month; $day++) {
            $result[] = [
                'day' => $day,
                'marcadas' => $bookings[$day]['marcadas'],
                'concluidas' => $bookings[$day]['concluidas'],
                'canceladas' => $bookings[$day]['canceladas']
            ];
        }
    } else {
        // Dados semanais (padrão)
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        $endOfWeek = date('Y-m-d', strtotime('saturday this week'));
        error_log('Intervalo semanal: ' . $startOfWeek . ' a ' . $endOfWeek);
        $query = "
            SELECT
                DAYOFWEEK(data_marcacao) AS day_of_week,
                estado,
                COUNT(*) AS booking_count
            FROM marcacoes
            WHERE data_marcacao BETWEEN :startOfWeek AND :endOfWeek
            GROUP BY DAYOFWEEK(data_marcacao), estado
            ORDER BY day_of_week ASC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':startOfWeek', $startOfWeek);
        $stmt->bindParam(':endOfWeek', $endOfWeek);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log('Dados brutos para weekly: ' . json_encode($rows));

        $diasSemana = [
            '2' => 'Segunda', '3' => 'Terça', '4' => 'Quarta',
            '5' => 'Quinta', '6' => 'Sexta', '7' => 'Sábado'
        ];

        $bookings = array_fill_keys(array_keys($diasSemana), ['marcadas' => 0, 'concluidas' => 0, 'canceladas' => 0]);

        foreach ($rows as $row) {
            $day = $row['day_of_week'];
            if (isset($bookings[$day])) {
                $bookings[$day][$row['estado']] = (int)$row['booking_count'];
            }
        }

        $result = [];
        foreach ($bookings as $key => $counts) {
            $result[] = [
                'day_of_week' => $diasSemana[$key],
                'marcadas' => $counts['marcadas'],
                'concluidas' => $counts['concluidas'],
                'canceladas' => $counts['canceladas']
            ];
        }
    }

    error_log('Dados retornados para period=' . $period . ': ' . json_encode($result));

    // Devolve os dados no formato JSON
    header('Content-Type: application/json');
    echo json_encode($result);

} catch (PDOException $e) {
    error_log('Erro no getBookingsData.php: ' . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ocorreu um erro na consulta à base de dados: ' . $e->getMessage()]);
}
?>