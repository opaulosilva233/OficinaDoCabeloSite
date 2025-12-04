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

try {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $limit = 50;
    $startIndex = ($page - 1) * $limit;

    $query = "
        SELECT 
            id, 
            nome_utilizador AS nome, 
            telefone_utilizador AS telefone, 
            DATE_FORMAT(data_marcacao, '%d/%m/%Y') AS data, 
            horario_marcacao AS horario, 
            estado 
        FROM marcacoes 
        WHERE 
            (nome_utilizador LIKE :search OR telefone_utilizador LIKE :search) AND
            estado = 'marcada'
        ORDER BY data_marcacao DESC, horario_marcacao DESC 
        LIMIT :startIndex, :limit
    ";
    
    $stmt = $db->prepare($query);
    $searchParam = "%$search%";
    $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
    $stmt->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalQuery = "
        SELECT COUNT(*) AS total 
        FROM marcacoes 
        WHERE 
            (nome_utilizador LIKE :search OR telefone_utilizador LIKE :search) AND
            estado = 'marcada'
    ";
    $totalStmt = $db->prepare($totalQuery);
    $totalStmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
    $totalStmt->execute();
    $totalResults = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalResults / $limit);

    header('Content-Type: application/json');
    echo json_encode([
        'data' => $appointments,
        'totalPages' => $totalPages,
        'currentPage' => $page,
    ]);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Erro na base de dados: ' . $e->getMessage()]);
}
?>