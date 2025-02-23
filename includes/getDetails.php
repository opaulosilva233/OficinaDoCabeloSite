<?php
include 'db.php';

$id = $_GET['id'];

try {
    // Consulta para obter os detalhes da marcação específica
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
            estado, 
            criado_em, 
            atualizado_em 
        FROM marcacoes 
        WHERE id = :id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $marcacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$marcacao) {
        http_response_code(404);
        echo json_encode(['error' => 'Marcação não encontrada']);
        exit;
    }

    // Consulta para calcular o total de marcações da pessoa
    $countQuery = "
        SELECT COUNT(*) AS total 
        FROM marcacoes 
        WHERE nome_utilizador = :nome
    ";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute(['nome' => $marcacao['nome']]);
    $totalMarcacoes = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta para buscar as últimas 5 marcações da pessoa
    $ultimasMarcacoesQuery = "
        SELECT 
            DATE_FORMAT(data_marcacao, '%d/%m/%Y') AS data, 
            horario_marcacao AS horario, 
            servico, 
            barbeiro 
        FROM marcacoes 
        WHERE nome_utilizador = :nome 
        ORDER BY data_marcacao DESC, horario_marcacao DESC 
        LIMIT 5
    ";
    $ultimasMarcacoesStmt = $pdo->prepare($ultimasMarcacoesQuery);
    $ultimasMarcacoesStmt->execute(['nome' => $marcacao['nome']]);
    $ultimasMarcacoes = $ultimasMarcacoesStmt->fetchAll(PDO::FETCH_ASSOC);

    // Retornar os dados como JSON
    echo json_encode([
        'id' => $marcacao['id'],
        'nome' => $marcacao['nome'],
        'telefone' => $marcacao['telefone'],
        'email' => $marcacao['email'],
        'barbeiro' => $marcacao['barbeiro'],
        'servico' => $marcacao['servico'],
        'data' => $marcacao['data'],
        'horario' => $marcacao['horario'],
        'estado' => $marcacao['estado'],
        'criado_em' => $marcacao['criado_em'],
        'atualizado_em' => $marcacao['atualizado_em'],
        'total_marcacoes' => $totalMarcacoes,
        'ultimas_marcacoes' => $ultimasMarcacoes // Últimas 5 marcações
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar dados: ' . $e->getMessage()]);
}
?>