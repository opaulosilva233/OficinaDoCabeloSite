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
function sendResponse($success, $message = '') {
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}

// Validação dos parâmetros
if (!isset($_POST['lock_id'])) {
    sendResponse(false, 'Parâmetro lock_id é obrigatório');
}

// Obtém o parâmetro e sanitiza
$lock_id = htmlspecialchars($_POST['lock_id'], ENT_QUOTES, 'UTF-8');

// Validação adicional do parâmetro
if (empty($lock_id)) {
    sendResponse(false, 'lock_id inválido');
}

// Verifica se a conexão com o banco de dados está disponível
if (!isset($pdo) || !($pdo instanceof PDO)) {
    sendResponse(false, 'Erro de conexão com o banco de dados');
}

try {
    // Atualiza o estado do registro para "cancelada"
    $stmt = $pdo->prepare('UPDATE marcacoes SET estado = "cancelada", atualizado_em = NOW() WHERE id = :id AND estado = "pendente"');
    $stmt->execute(['id' => $lock_id]);

    if ($stmt->rowCount() > 0) {
        sendResponse(true, 'Horário liberado com sucesso');
    } else {
        sendResponse(false, 'Nenhum registro pendente encontrado para este lock_id');
    }
} catch (Exception $e) {
    sendResponse(false, 'Erro ao liberar o horário: ' . $e->getMessage());
}
?>