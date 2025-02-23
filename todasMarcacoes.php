<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas as Marcações</title>
    <link rel="stylesheet" href="./css/todasMarcacoes.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <?php include 'includes/navbarLateral.php'; ?>
</head>
<body>
    <div class="container">
        <h1>Todas as Marcações</h1>
        <!-- Barra de Pesquisa -->
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Pesquisar por nome ou telefone..." />
            <button onclick="fetchAppointments(1)">Pesquisar</button>
        </div>
        <!-- Tabela de Marcações -->
        <table id="appointmentsTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Data e Horário</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dados serão inseridos aqui via JavaScript -->
            </tbody>
        </table>
        <!-- Paginação -->
        <div class="pagination">
            <button id="prevPage" onclick="changePage(-1)">Anterior</button>
            <span id="currentPage">Página 1</span>
            <button id="nextPage" onclick="changePage(1)">Próxima</button>
        </div>

        <!-- Modal -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <h2>Detalhes da Marcação</h2>

        <div class="info-group">
            <h3>Dados Pessoais</h3>
            <p><strong>ID:</strong> <span id="modal-id"></span></p>
            <p><strong>Nome:</strong> <span id="modal-nome"></span></p>
            <p><strong>Telefone:</strong> <span id="modal-telefone"></span></p>
            <p><strong>Email:</strong> <span id="modal-email"></span></p>
        </div>

        <div class="info-group">
            <h3>Serviço</h3>
            <p><strong>Barbeiro:</strong> <span id="modal-barbeiro"></span></p>
            <p><strong>Serviço:</strong> <span id="modal-servico"></span></p>
            <p><strong>Data e Horário:</strong> <span id="modal-data-horario"></span></p>
            <p><strong>Estado:</strong> <span id="modal-estado"></span></p>
        </div>

        <div class="info-group">
            <h3>Status e Histórico</h3>
            <p><strong>Criado Em:</strong> <span id="modal-criado-em"></span></p>
            <p><strong>Atualizado Em:</strong> <span id="modal-atualizado-em"></span></p>
            <p><strong>Total de Marcações:</strong> <span id="modal-total-marcacoes"></span></p>

            <h4>Últimas 5 Marcações</h4>
            <table id="ultimas-marcacoes-table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Serviço</th>
                        <th>Estado</th>
                        <th>Barbeiro</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Linhas serão preenchidas dinamicamente pelo JavaScript -->
                </tbody>
            </table>
        </div>

        <button class="close-button" onclick="closeModal()">Fechar</button>
    </div>
</div>
    </div>
    <script src="./js/todasMarcacoes.js"></script>
</body>
</html>