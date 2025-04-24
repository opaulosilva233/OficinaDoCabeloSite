<?php
// Garantir que o PHP use UTF-8
header('Content-Type: application/json; charset=UTF-8');

// Incluir a conexão com o banco de dados
require_once 'db.php';

// Garante que não há saída antes do JSON
ob_start();

// Inicializar a resposta
$response = ['success' => false, 'message' => ''];

// Receber os dados do formulário
$service = isset($_POST['service']) ? trim($_POST['service']) : '';
$barber = isset($_POST['barber']) ? trim($_POST['barber']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$time = isset($_POST['time']) ? trim($_POST['time']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Log dos dados recebidos para depuração
error_log("Dados recebidos: " . json_encode([
    'service' => $service,
    'barber' => $barber,
    'date' => $date,
    'time' => $time,
    'name' => $name,
    'phone' => $phone,
    'email' => $email
]));

try {
    // Validar os dados
    if (empty($service) || empty($barber) || empty($date) || empty($time) || empty($name) || empty($phone) || empty($email)) {
        $response['message'] = 'Por favor, preencha todos os campos';
        error_log("Erro: Campos obrigatórios vazios - servico=$service, barbeiro=$barber, data_marcacao=$date, horario_marcacao=$time, nome_utilizador=$name, telefone_utilizador=$phone, email_utilizador=$email");
        echo json_encode($response);
        exit;
    }

    // Validar o formato da data (esperado: YYYY-MM-DD, ex.: 2025-04-24)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $response['message'] = 'Formato de data inválido. Use o formato YYYY-MM-DD';
        error_log("Erro: Formato de data inválido - data_marcacao=$date");
        echo json_encode($response);
        exit;
    }

    // Verificar se o horário ainda está disponível (redundância para segurança)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM marcacoes WHERE barbeiro = ? AND data_marcacao = ? AND horario_marcacao = ? AND estado = 'marcada'");
    $stmt->execute([$barber, $date, $time]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $response['message'] = 'O horário selecionado já não está disponível';
        error_log("Erro: Horário já reservado - barbeiro=$barber, data_marcacao=$date, horario_marcacao=$time");
        echo json_encode($response);
        exit;
    }

    // Log para depuração antes da inserção
    error_log("Inserindo marcação: servico=$service, barbeiro=$barber, data_marcacao=$date, horario_marcacao=$time, nome_utilizador=$name, telefone_utilizador=$phone, email_utilizador=$email, estado=marcada");

    // Inserir os dados no banco de dados
    $stmt = $pdo->prepare("INSERT INTO marcacoes (servico, barbeiro, data_marcacao, horario_marcacao, nome_utilizador, telefone_utilizador, email_utilizador, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 'marcada')");
    $success = $stmt->execute([$service, $barber, $date, $time, $name, $phone, $email]);

    // Verificar se a inserção foi bem-sucedida
    if (!$success) {
        $errorInfo = $stmt->errorInfo();
        $response['message'] = 'Erro ao salvar a marcação: ' . $errorInfo[2];
        error_log("Erro ao executar a query de inserção: " . $errorInfo[2]);
        echo json_encode($response);
        exit;
    }

    $insertedRows = $stmt->rowCount();
    if ($insertedRows === 0) {
        $response['message'] = 'Erro ao salvar a marcação: nenhuma linha inserida';
        error_log("Falha ao inserir a marcação: nenhuma linha foi inserida.");
        echo json_encode($response);
        exit;
    }

    // Consultar a linha recém-inserida para depuração
    $lastInsertId = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT data_marcacao, horario_marcacao FROM marcacoes WHERE id = ?");
    $stmt->execute([$lastInsertId]);
    $savedData = $stmt->fetch(PDO::FETCH_ASSOC);
    error_log("Dados salvos na base de dados: data_marcacao=" . ($savedData['data_marcacao'] ?? 'NULL') . ", horario_marcacao=" . ($savedData['horario_marcacao'] ?? 'NULL'));

    // Definir resposta de sucesso
    $response['success'] = true;
    $response['message'] = 'Marcação salva com sucesso!';

} catch (PDOException $e) {
    $response['message'] = 'Erro ao salvar a marcação: ' . $e->getMessage();
    error_log("Erro ao salvar a marcação: " . $e->getMessage());
    echo json_encode($response);
    exit;
}

// Limpa qualquer saída indesejada antes do JSON
ob_end_clean();

// Envia a resposta JSON
echo json_encode($response);
exit;