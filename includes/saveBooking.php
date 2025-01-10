<?php
var_dump($_POST);
include('db.php'); // Inclui o arquivo de conexão com o banco de dados

// Verifique se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados do formulário
    $service = isset($_POST['service']) ? $_POST['service'] : null;
    $barber = isset($_POST['barber']) ? $_POST['barber'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;
    $time = isset($_POST['time']) ? $_POST['time'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    // Inicializa um array para armazenar mensagens de erro
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
        // Se houver erros, redireciona para erroMarcacao.php com a mensagem de erro
        $errorMessage = urlencode(implode(' ', $errors));
        header("Location: /erroMarcacao.php?error=" . $errorMessage); // Redireciona para erroMarcacao.php
        exit;
    }

    // Convertendo a data de "DD-MM-YYYY" para "YYYY-MM-DD"
    if ($date) {
        $dateParts = explode('-', $date);
        if (count($dateParts) == 3) {
            // A data está no formato "DD-MM-YYYY"
            $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0]; // "YYYY-MM-DD"
        } else {
            // Se o formato da data estiver errado
            $errorMessage = urlencode('Formato de data inválido.');
            header("Location: /erroMarcacao.php?error=" . $errorMessage); // Redireciona para erroMarcacao.php
            exit;
        }
    }

    // Preparar a consulta SQL com prepared statements
    $stmt = $conn->prepare("INSERT INTO marcacoes (nome_utilizador, telefone_utilizador, email_utilizador, barbeiro, servico, data_marcacao, horario_marcacao, estado, criado_em, atualizado_em) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Verificar se a consulta foi preparada corretamente
    if ($stmt === false) {
        $errorMessage = urlencode('Erro ao preparar a consulta SQL.');
        header("Location: /erroMarcacao.php?error=" . $errorMessage); // Redireciona para erroMarcacao.php
        exit;
    }

    // Definindo os valores para os parâmetros
    $status = 'marcada'; // Status inicial da marcação
    $created_at = date('Y-m-d H:i:s'); // Data e hora de criação
    $updated_at = $created_at; // Data e hora de atualização (inicialmente igual à criação)

    // Bind dos parâmetros
    $stmt->bind_param("ssssssssss", $name, $phone, $email, $barber, $service, $formattedDate, $time, $status, $created_at, $updated_at);

    // Executar a consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reserva realizada com sucesso!']);
    } else {
        $errorMessage = urlencode('Erro ao salvar a reserva: ' . $stmt->error);
        header("Location: /erroMarcacao.php?error=" . $errorMessage); // Redireciona para erroMarcacao.php
        exit;
    }

    // Fechar a consulta e a conexão
    $stmt->close();
    mysqli_close($conn);
}
?>
