<?php
// Garantir que o PHP use UTF-8
header('Content-Type: text/html; charset=UTF-8');

// Incluir a conexão com o banco de dados
require_once 'db.php';

// Receber os dados do formulário
$service = isset($_POST['service']) ? trim($_POST['service']) : '';
$barber = isset($_POST['barber']) ? trim($_POST['barber']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$time = isset($_POST['time']) ? trim($_POST['time']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Validar os dados
if (empty($service) || empty($barber) || empty($date) || empty($time) || empty($name) || empty($phone) || empty($email)) {
    echo "<script>console.log('Erro: Campos obrigatórios vazios - servico=$service, barbeiro=$barber, data_marcacao=$date, horario_marcacao=$time, nome_utilizador=$name, telefone_utilizador=$phone, email_utilizador=$email');</script>";
    header("Location: ../marcacoes.php?error=Por favor, preencha todos os campos");
    exit;
}

// Validar o formato da data (esperado: YYYY-MM-DD, ex.: 2025-04-24)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo "<script>console.log('Erro: Formato de data inválido - data_marcacao=$date');</script>";
    header("Location: ../marcacoes.php?error=Formato de data inválido. Use o formato YYYY-MM-DD");
    exit;
}

// Verificar se o horário ainda está disponível (redundância para segurança)
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM marcacoes WHERE barbeiro = ? AND data_marcacao = ? AND horario_marcacao = ? AND estado = 'marcada'");
    $stmt->execute([$barber, $date, $time]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo "<script>console.log('Erro: Horário já reservado - barbeiro=$barber, data_marcacao=$date, horario_marcacao=$time');</script>";
        header("Location: ../marcacoes.php?error=O horário selecionado já não está disponível");
        exit;
    }

    // Log para depuração antes da inserção
    echo "<script>console.log('Inserindo marcação: servico=$service, barbeiro=$barber, data_marcacao=$date, horario_marcacao=$time, nome_utilizador=$name, telefone_utilizador=$phone, email_utilizador=$email, estado=marcada');</script>";

    // Inserir os dados no banco de dados
    $stmt = $pdo->prepare("INSERT INTO marcacoes (servico, barbeiro, data_marcacao, horario_marcacao, nome_utilizador, telefone_utilizador, email_utilizador, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 'marcada')");
    $success = $stmt->execute([$service, $barber, $date, $time, $name, $phone, $email]);

    // Verificar se a inserção foi bem-sucedida
    if (!$success) {
        $errorInfo = $stmt->errorInfo();
        echo "<script>console.log('Erro ao executar a query de inserção: " . addslashes($errorInfo[2]) . "');</script>";
        header("Location: ../marcacoes.php?error=Erro ao salvar a marcação: " . urlencode($errorInfo[2]));
        exit;
    }

    $insertedRows = $stmt->rowCount();
    if ($insertedRows === 0) {
        echo "<script>console.log('Falha ao inserir a marcação: nenhuma linha foi inserida.');</script>";
        header("Location: ../marcacoes.php?error=Erro ao salvar a marcação: nenhuma linha inserida");
        exit;
    }

    // Consultar a linha recém-inserida para depuração
    $lastInsertId = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT data_marcacao, horario_marcacao FROM marcacoes WHERE id = ?");
    $stmt->execute([$lastInsertId]);
    $savedData = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<script>console.log('Dados salvos na base de dados: data_marcacao=" . ($savedData['data_marcacao'] ?? 'NULL') . ", horario_marcacao=" . ($savedData['horario_marcacao'] ?? 'NULL') . "');</script>";

    // Redirecionar para a página de confirmação
    header("Location: ../marcacoes.php?date=$date&time=$time&barber=" . urlencode($barber) . "&name=" . urlencode($name) . "&service=" . urlencode($service));
    exit;
} catch (PDOException $e) {
    echo "<script>console.log('Erro ao salvar a marcação: " . addslashes($e->getMessage()) . "');</script>";
    header("Location: ../marcacoes.php?error=Erro ao salvar a marcação: " . urlencode($e->getMessage()));
    exit;
}
?>