<?php
// Configurações para evitar saída indesejada
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

// Define o cabeçalho como JSON
header('Content-Type: application/json');

// Inclui o arquivo de conexão com o banco de dados
require_once 'db.php';

// Função para retornar uma resposta JSON e encerrar o script
function sendResponse($success, $lock_id = null, $message = '') {
    echo json_encode([
        'success' => $success,
        'lock_id' => $lock_id,
        'message' => $message
    ]);
    exit;
}

// Validação dos parâmetros
if (!isset($_POST['barber']) || !isset($_POST['date']) || !isset($_POST['time'])) {
    sendResponse(false, null, 'Parâmetros barbeiro, data e horário são obrigatórios');
}

// Obtém os parâmetros e sanitiza
$barber = htmlspecialchars($_POST['barber'], ENT_QUOTES, 'UTF-8');
$date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
$time = htmlspecialchars($_POST['time'], ENT_QUOTES, 'UTF-8');

// Validação adicional dos parâmetros
if (empty($barber) || empty($date) || empty($time)) {
    sendResponse(false, null, 'Barbeiro, data ou horário inválidos');
}

// Verifica se a conexão com o banco de dados está disponível
if (!isset($pdo) || !($pdo instanceof PDO)) {
    sendResponse(false, null, 'Erro de conexão com o banco de dados');
}

try {
    // Inicia uma transação para garantir consistência
    $pdo->beginTransaction();

    // Verifica se o horário já está reservado
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM marcacoes WHERE barbeiro = :barbeiro AND data_marcacao = :data_marcacao AND horario_marcacao = :horario_marcacao AND estado != "cancelada"');
    $stmt->execute([
        'barbeiro' => $barber,
        'data_marcacao' => $date,
        'horario_marcacao' => $time
    ]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $pdo->rollBack();
        sendResponse(false, null, 'Este horário já está reservado');
    }

    // Insere um registro temporário para bloquear o horário
    // Aqui, vamos usar um estado temporário, como "pendente", para indicar que o horário está bloqueado
    $stmt = $pdo->prepare('INSERT INTO marcacoes (barbeiro, data_marcacao, horario_marcacao, estado, criado_em) VALUES (:barbeiro, :data_marcacao, :horario_marcacao, "pendente", NOW())');
    $stmt->execute([
        'barbeiro' => $barber,
        'data_marcacao' => $date,
        'horario_marcacao' => $time
    ]);

    // Obtém o ID do registro inserido (será usado como lock_id)
    $lock_id = $pdo->lastInsertId();

    // Confirma a transação
    $pdo->commit();

    sendResponse(true, $lock_id);
} catch (Exception $e) {
    // Em caso de erro, faz rollback da transação
    $pdo->rollBack();
    sendResponse(false, null, 'Erro ao reservar o horário: ' . $e->getMessage());
}
?>