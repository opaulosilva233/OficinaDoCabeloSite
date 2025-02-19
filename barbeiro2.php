<?php
// Incluir a conexão com a base de dados
include 'includes/db.php';
try {
    // Obtém a data e hora atuais
    $data_atual = date('Y-m-d');
    $hora_atual = date('H:i:s');
    // Consulta para buscar as marcações futuras do barbeiro Hugo Alves
    $sql = "SELECT id, nome_utilizador, telefone_utilizador, email_utilizador, servico, data_marcacao, horario_marcacao, estado 
            FROM marcacoes 
            WHERE barbeiro = 'Hugo Alves' 
            AND estado NOT IN ('concluída', 'cancelada')
            AND (data_marcacao > :data_atual OR (data_marcacao = :data_atual AND horario_marcacao >= :hora_atual))
            ORDER BY data_marcacao ASC, horario_marcacao ASC";
    
    // Preparar e executar a consulta
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':data_atual', $data_atual);
    $stmt->bindParam(':hora_atual', $hora_atual);
    $stmt->execute();
    $marcacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar marcações: " . $e->getMessage());
}
// Atualização do estado da marcação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
    $id = $_POST['id'];
    $novo_estado = $_POST['estado'];
    
    try {
        $update_sql = "UPDATE marcacoes SET estado = :estado WHERE id = :id";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':estado', $novo_estado);
        $update_stmt->bindParam(':id', $id);
        $update_stmt->execute();
        
        // Redireciona para evitar reenvio do formulário
        header("Location: barbeiro1.php");
        exit();
    } catch (PDOException $e) {
        die("Erro ao atualizar estado da marcação: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hugo Alves - Marcações</title>
    <link rel="stylesheet" href="css/barbeiro1.css"> <!-- Arquivo CSS para estilização -->
    <style>
        /* Estilo para o modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Incluir a Navbar -->
    <?php include 'includes/navbarLateral.php'; ?>
    <div class="container">
        <h1>Marcações - Hugo Alves</h1>
        
        <table class="appointments-table" id="appointmentsTable">
    <thead>
        <tr>
            <th data-column="id">#</th>
            <th data-column="nome">Nome</th>
            <th data-column="telefone">Telefone</th>
            <th data-column="email">Email</th>
            <th data-column="servico">Serviço</th>
            <th data-column="data">Data</th>
            <th data-column="hora">Hora</th>
            <th data-column="estado">Estado</th>
            <th>Ações</th>
        </tr>
    </thead>
            <tbody>
                <?php
                if (!empty($marcacoes)) {
                    foreach ($marcacoes as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nome_utilizador']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['telefone_utilizador']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email_utilizador']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['servico']) . "</td>";
                        echo "<td>" . date('d/m/Y', strtotime($row['data_marcacao'])) . "</td>";
                        echo "<td>" . date('H:i', strtotime($row['horario_marcacao'])) . "</td>";
                        echo "<td>" . ucfirst(htmlspecialchars($row['estado'])) . "</td>";
                        echo "<td>"
                            . "<form id='form-" . $row['id'] . "' method='POST' style='display:inline;'>"
                            . "<input type='hidden' name='id' value='" . $row['id'] . "'>"
                            . "<input type='hidden' name='estado' value=''>"
                            . "<button type='button' onclick='abrirModal(" . $row['id'] . ", \"concluída\")'>✅</button>"
                            . "<button type='button' onclick='abrirModal(" . $row['id'] . ", \"cancelada\")'>❌</button>"
                            . "</form>"
                            . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>Nenhuma marcação futura encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="fecharModal()">&times;</span>
                <p id="modal-message"></p>
                <button id="confirm-button" onclick="confirmarAcao()">Confirmar</button>
                <button onclick="fecharModal()">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Incluir o arquivo JavaScript -->
    <script src="js/confirmBarbeiro.js"></script>
</body>
</html>