<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($barberName) ?> - Marcações</title>
    <link rel="stylesheet" href="<?= $cssFile ?>">
    <link rel="stylesheet" href="assets/css/transitions.css">
</head>
<body>
<!-- Page Transition Overlay -->
<div class="page-transition-overlay">
    <div class="transition-panel"></div>
    <div class="transition-panel"></div>
    <div class="transition-panel"></div>
</div>
    <?php include 'includes/navbarLateral.php'; ?>
    <div class="container">
        <h1>Marcações - <?= htmlspecialchars($barberName) ?></h1>
        
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <button class="toggle-button" onclick="toggleTables()">Ver Marcações Passadas Pendentes</button>

        <!-- Future Appointments -->
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
                <?php if (!empty($marcacoes_futuras)): ?>
                    <?php foreach ($marcacoes_futuras as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['nome_utilizador']) ?></td>
                            <td><?= htmlspecialchars($row['telefone_utilizador']) ?></td>
                            <td><?= htmlspecialchars($row['email_utilizador']) ?></td>
                            <td><?= htmlspecialchars($row['servico']) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['data_marcacao'])) ?></td>
                            <td><?= date('H:i', strtotime($row['horario_marcacao'])) ?></td>
                            <td><?= ucfirst(htmlspecialchars($row['estado'])) ?></td>
                            <td>
                                <form id="form-<?= $row['id'] ?>" method="POST" action="index.php?route=agenda&barber=<?= urlencode($barberName) ?>" style="display:inline;">
                                    <?= CSRF::renderInput() ?>
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="estado" value="">
                                    <button type="button" onclick="abrirModal(<?= $row['id'] ?>, 'concluída')">✅</button>
                                    <button type="button" onclick="abrirModal(<?= $row['id'] ?>, 'cancelada')">❌</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9">Nenhuma marcação futura encontrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Past Pending Appointments -->
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
                <?php if (!empty($marcacoes_passadas_pendentes)): ?>
                    <?php foreach ($marcacoes_passadas_pendentes as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['nome_utilizador']) ?></td>
                            <td><?= htmlspecialchars($row['telefone_utilizador']) ?></td>
                            <td><?= htmlspecialchars($row['email_utilizador']) ?></td>
                            <td><?= htmlspecialchars($row['servico']) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['data_marcacao'])) ?></td>
                            <td><?= date('H:i', strtotime($row['horario_marcacao'])) ?></td>
                            <td><?= ucfirst(htmlspecialchars($row['estado'])) ?></td>
                            <td>
                                <form id="form-<?= $row['id'] ?>" method="POST" action="index.php?route=agenda&barber=<?= urlencode($barberName) ?>" style="display:inline;">
                                    <?= CSRF::renderInput() ?>
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="estado" value="">
                                    <button type="button" onclick="abrirModal(<?= $row['id'] ?>, 'concluída')">✅</button>
                                    <button type="button" onclick="abrirModal(<?= $row['id'] ?>, 'cancelada')">❌</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9">Nenhuma marcação passada pendente encontrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="icon"></div>
                <p id="modal-message"></p>
                <button id="confirm-button" onclick="confirmarAcao()">Confirmar</button>
                <button onclick="fecharModal()">Cancelar</button>
            </div>
        </div>
    </div>
    <script src="assets/js/barbeirosMarc.js"></script>
    <script src="assets/js/transitions.js"></script>
</body>
</html>
