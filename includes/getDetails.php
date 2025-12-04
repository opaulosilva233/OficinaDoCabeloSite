<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../Database.php';

$auth = new Auth();
if (!$auth->isLoggedIn()) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Acesso negado']);
    exit;
}

$db = Database::getInstance()->getConnection();
$id = $_GET['id'] ?? 0;

try {
    $query = "
        SELECT 
            id, 
            nome_utilizador AS nome, 
            telefone_utilizador AS telefone, 
            email_utilizador AS email, 
            barbeiro, 
            servico, 
            DATE_FORMAT(data_marcacao, '%d/%m/%Y') AS data, 
            horario_marcacao AS horario, 
            LOWER(estado) AS estado,
            criado_em, 
            atualizado_em 
        FROM marcacoes 
        WHERE id = :id
    ";
    $stmt = $db->prepare($query);
    $stmt->execute(['id' => $id]);
    $marcacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$marcacao) {
        http_response_code(404);
        echo json_encode(['error' => 'Marcação não encontrada']);
        exit;
    }

    $estado = strtolower($marcacao['estado'] ?? 'pendente');
    if ($estado === 'marcada') {
        $estado = 'pendente';
    }

    $countQuery = "SELECT COUNT(*) AS total FROM marcacoes WHERE nome_utilizador = :nome";
    $countStmt = $db->prepare($countQuery);
    $countStmt->execute(['nome' => $marcacao['nome']]);
    $totalMarcacoes = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $ultimasMarcacoesQuery = "
        SELECT 
            DATE_FORMAT(data_marcacao, '%d/%m/%Y') AS data, 
            horario_marcacao AS horario, 
            servico, 
            barbeiro 
        FROM marcacoes 
        WHERE nome_utilizador = :nome 
        ORDER BY data_marcacao DESC, horario_marcacao DESC LIMIT 5
    ";
    $ultimasMarcacoesStmt = $db->prepare($ultimasMarcacoesQuery);
    $ultimasMarcacoesStmt->execute(['nome' => $marcacao['nome']]);
    $ultimasMarcacoes = $ultimasMarcacoesStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'id' => $marcacao['id'],
        'nome' => $marcacao['nome'],
        'telefone' => $marcacao['telefone'],
        'email' => $marcacao['email'],
        'barbeiro' => $marcacao['barbeiro'],
        'servico' => $marcacao['servico'],
        'data' => $marcacao['data'],
        'horario' => $marcacao['horario'],
        'estado' => $estado,
        'criado_em' => $marcacao['criado_em'],
        'atualizado_em' => $marcacao['atualizado_em'],
        'total_marcacoes' => $totalMarcacoes,
        'ultimas_marcacoes' => $ultimasMarcacoes
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro: ' . $e->getMessage()]);
}
?>