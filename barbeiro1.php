<?php
// Incluir a conexão com a base de dados
include './includes/db.php';

try {
    // Obtém a data e hora atuais
    $data_atual = date('Y-m-d');
    $hora_atual = date('H:i:s');

    // Consulta para buscar as marcações futuras do barbeiro Bruno Martins
    $sql_futuras = "
        SELECT id, nome_utilizador, telefone_utilizador, email_utilizador, servico, data_marcacao, horario_marcacao, estado 
        FROM marcacoes 
        WHERE barbeiro = :barbeiro 
        AND estado NOT IN ('concluída', 'cancelada')
        AND (data_marcacao > :data_atual OR (data_marcacao = :data_atual AND horario_marcacao >= :hora_atual))
        ORDER BY data_marcacao ASC, horario_marcacao ASC
    ";

    // Consulta para buscar as marcações passadas pendentes do barbeiro Bruno Martins
    $sql_passadas_pendentes = "
        SELECT id, nome_utilizador, telefone_utilizador, email_utilizador, servico, data_marcacao, horario_marcacao, estado 
        FROM marcacoes 
        WHERE barbeiro = :barbeiro 
        AND estado = 'marcada'
        AND (data_marcacao < :data_atual OR (data_marcacao = :data_atual AND horario_marcacao < :hora_atual))
        ORDER BY data_marcacao DESC, horario_marcacao DESC
    ";

    // Preparar e executar as consultas
    $stmt_futuras = $pdo->prepare($sql_futuras);
    $stmt_futuras->bindParam(':barbeiro', $barbeiro);
    $stmt_futuras->bindParam(':data_atual', $data_atual);
    $stmt_futuras->bindParam(':hora_atual', $hora_atual);

    $stmt_passadas_pendentes = $pdo->prepare($sql_passadas_pendentes);
    $stmt_passadas_pendentes->bindParam(':barbeiro', $barbeiro);
    $stmt_passadas_pendentes->bindParam(':data_atual', $data_atual);
    $stmt_passadas_pendentes->bindParam(':hora_atual', $hora_atual);

    // Define o nome do barbeiro
    $barbeiro = 'Bruno Martins';

    $stmt_futuras->execute();
    $marcacoes_futuras = $stmt_futuras->fetchAll(PDO::FETCH_ASSOC);

    $stmt_passadas_pendentes->execute();
    $marcacoes_passadas_pendentes = $stmt_passadas_pendentes->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Bruno Martins - Marcações</title>
    <!-- Link para o CSS -->
    <link rel="stylesheet" href="./css/barbeiro1.css">
</head>
<body>
    <!-- Incluir a Navbar -->
    <?php include 'includes/navbarLateral.php'; ?>
    <div class="container">
        <h1>Marcações - Bruno Martins</h1>

        <!-- Botão de Alternância -->
        <button class="toggle-button" onclick="toggleTables()">Ver Marcações Passadas Pendentes</button>

        <!-- Tabela de Marcações Futuras -->
        <table class="appointments-table" id="futureAppointmentsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Serviço</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($marcacoes_futuras)) {
                    foreach ($marcacoes_futuras as $row) {
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

        <!-- Tabela de Marcações Passadas Pendentes -->
        <table class="appointments-table" id="pastPendingAppointmentsTable" style="display: none;">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Serviço</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($marcacoes_passadas_pendentes)) {
                    foreach ($marcacoes_passadas_pendentes as $row) {
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
                    echo "<tr><td colspan='9'>Nenhuma marcação passada pendente encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Modal -->
        <div id="myModal" class="modal">
         <div class="modal-content">
        <!-- Ícone para reforçar a ação -->
        <div class="icon"></div>
        <p id="modal-message"></p>
        <button id="confirm-button" onclick="confirmarAcao()">Confirmar</button>
        <button onclick="fecharModal()">Cancelar</button>
    </div>
</div>
    </div>

    <!-- Script para alternar entre tabelas -->
    <script src="./js/barbeirosMarc.js"></script>
</body>
</html>