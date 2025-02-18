<?php
// Incluir o arquivo de conexão com o banco de dados
include('db.php');

// Verificar se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber os dados do formulário
    $service = isset($_POST['service']) ? $_POST['service'] : null;
    $barber = isset($_POST['barber']) ? $_POST['barber'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $time = isset($_POST['time']) ? $_POST['time'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    // Log dos dados recebidos
    error_log("Dados recebidos: service=$service, barber=$barber, date=$date, time=$time, name=$name, phone=$phone, email=$email");

    // Inicializar um array para armazenar mensagens de erro
    $errors = [];

    // Validação de cada campo individualmente
    if (empty($service)) {
        $errors[] = 'O campo "Serviço" é obrigatório.';
    }
    if (empty($barber)) {
        $errors[] = 'O campo "Barbeiro" é obrigatório.';
    }
    if (empty($date)) {
        $errors[] = 'O campo "Data" é obrigatório.';
    }
    if (empty($time)) {
        $errors[] = 'O campo "Horário" é obrigatório.';
    }
    if (empty($name)) {
        $errors[] = 'O campo "Nome" é obrigatório.';
    }
    if (empty($phone)) {
        $errors[] = 'O campo "Telefone" é obrigatório.';
    }
    if (empty($email)) {
        $errors[] = 'O campo "Email" é obrigatório.';
    }

    // Verificar se há erros de validação
    if (!empty($errors)) {
        $errorMessage = implode(' ', $errors);
        error_log("Erros de validação: " . $errorMessage);
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }

    // Convertendo a data de "DD-MM-YYYY" para "YYYY-MM-DD"
    if ($date) {
        $dateParts = explode('-', $date);
        if (count($dateParts) == 3) {
            // A data está no formato "DD-MM-YYYY"
            $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0]; // "YYYY-MM-DD"
        } else {
            $errorMessage = 'Formato de data inválido.';
            error_log("Erro de formato de data: $date");
            echo json_encode(['success' => false, 'message' => $errorMessage]);
            exit;
        }
    }

    // Preparar a consulta SQL com prepared statements
    $sql = "INSERT INTO marcacoes (nome_utilizador, telefone_utilizador, email_utilizador, barbeiro, servico, data_marcacao, horario_marcacao, estado, criado_em, atualizado_em)
            VALUES (:name, :phone, :email, :barber, :service, :date, :time, :status, :created_at, :updated_at)";
    $stmt = $pdo->prepare($sql);

    // Verificar se a consulta foi preparada corretamente
    if ($stmt === false) {
        $errorMessage = 'Erro ao preparar a consulta SQL.';
        error_log("Erro ao preparar a consulta SQL: " . implode(' ', $pdo->errorInfo()));
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }

    // Definir os valores para os parâmetros
    $status = 'marcada'; // Status inicial da marcação
    $created_at = date('Y-m-d H:i:s'); // Data e hora de criação
    $updated_at = $created_at; // Data e hora de atualização (inicialmente igual à criação)

    // Bind dos parâmetros
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':barber', $barber);
    $stmt->bindParam(':service', $service);
    $stmt->bindParam(':date', $formattedDate);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->bindParam(':updated_at', $updated_at);

    // Executar a consulta
    if ($stmt->execute()) {
        error_log("Reserva realizada com sucesso para: service=$service, barber=$barber, date=$formattedDate, time=$time, name=$name, phone=$phone, email=$email");
        echo json_encode(['success' => true, 'message' => 'Reserva realizada com sucesso!']);
    } else {
        $errorMessage = 'Erro ao salvar a reserva: ' . implode(' ', $stmt->errorInfo());
        error_log("Erro ao salvar a reserva: " . implode(' ', $stmt->errorInfo()));
        echo json_encode(['success' => false, 'message' => $errorMessage]);
    }
} else {
    $errorMessage = 'Método de requisição inválido.';
    error_log("Método de requisição inválido: " . $_SERVER["REQUEST_METHOD"]);
    echo json_encode(['success' => false, 'message' => $errorMessage]);
}
?>
