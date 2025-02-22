<?php
include 'db.php'; // Inclui a conexão ao banco de dados

// Parâmetros da requisição
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Configurações de paginação
$limit = 50; // Máximo de 50 marcações por página
$startIndex = ($page - 1) * $limit;

// Consulta SQL para buscar as marcações
$query = "SELECT * FROM appointments WHERE name LIKE :search OR phone LIKE :search ORDER BY date DESC LIMIT :startIndex, :limit";
$stmt = $pdo->prepare($query);
$searchParam = "%$search%";
$stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
$stmt->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contagem total de marcações para calcular o número de páginas
$totalQuery = "SELECT COUNT(*) AS total FROM appointments WHERE name LIKE :search OR phone LIKE :search";
$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->bindValue(':search', $searchParam, PDO::PARAM_STR);
$totalStmt->execute();
$totalResults = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalResults / $limit);

// Retorna os resultados como JSON
header('Content-Type: application/json');
echo json_encode([
    'data' => $appointments,
    'totalPages' => $totalPages,
    'currentPage' => $page,
]);
?>