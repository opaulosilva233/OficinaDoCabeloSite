<?php  
include './db.php'; // Inclui a ligação à base de dados (caminho atualizado com './')  
  
try {  
    // Parâmetros da requisição  
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Número da página  
    $search = isset($_GET['search']) ? trim($_GET['search']) : ''; // Termo de pesquisa  
  
    // Configurações de paginação  
    $limit = 50; // Máximo de 50 marcações por página  
    $startIndex = ($page - 1) * $limit; // Índice inicial para a consulta  
  
    // Consulta SQL para buscar as marcações  
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
            (nome_utilizador LIKE :search OR   
            telefone_utilizador LIKE :search) AND
            estado = 'marcada'
        ORDER BY data_marcacao DESC, horario_marcacao DESC   
        LIMIT :startIndex, :limit  
    ";  
    $stmt = $pdo->prepare($query);  
    $searchParam = "%$search%";  
    $stmt->bindValue(':search', $searchParam, PDO::PARAM_STR);  
    $stmt->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);  
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);  
    $stmt->execute();  
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);  
  
    // Contagem total de marcações para calcular o número de páginas  
    $totalQuery = "  
        SELECT COUNT(*) AS total   
        FROM marcacoes   
        WHERE   
            (nome_utilizador LIKE :search OR   
            telefone_utilizador LIKE :search) AND
            estado = 'marcada'  
    ";  
    $totalStmt = $pdo->prepare($totalQuery);  
    $totalStmt->bindValue(':search', $searchParam, PDO::PARAM_STR);  
    $totalStmt->execute();  
    $totalResults = $totalStmt->fetch(PDO::FETCH_ASSOC)['total'];  
    $totalPages = ceil($totalResults / $limit); // Cálculo do número total de páginas  
  
    // Define o cabeçalho como JSON  
    header('Content-Type: application/json');  
  
    // Retorna os resultados como JSON, mantendo as chaves originais  
    echo json_encode([  
        'data' => $appointments, // Lista de marcações  
        'totalPages' => $totalPages, // Total de páginas  
        'currentPage' => $page, // Página atual  
    ]);  
} catch (PDOException $e) {  
    // Em caso de erro, devolve uma mensagem de erro em formato JSON  
    header('Content-Type: application/json');  
    echo json_encode([  
        'error' => 'Ocorreu um erro na consulta à base de dados.',  
        'details' => $e->getMessage(),  
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);  
} catch (Exception $e){
    header('Content-Type: application/json');  
    echo json_encode([  
        'error' => 'Ocorreu um erro.',  
        'details' => $e->getMessage(),  
        'code' => $e->getCode(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);  
}
?>