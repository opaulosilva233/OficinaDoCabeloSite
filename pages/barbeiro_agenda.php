<?php
$path_prefix = '../';
require_once '../includes/models/Auth.php';
require_once '../includes/models/Appointment.php';

$auth = new Auth();
$auth->requireLogin();

$appointmentModel = new Appointment();

// Get barber name from URL or default
$barberName = $_GET['barber'] ?? 'Bruno Martins';
// Simple validation to prevent arbitrary strings if needed, or just trust the input for now
// Ideally, we'd have a Barber model or array of valid barbers.

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
    $id = $_POST['id'];
    $novo_estado = $_POST['estado'];
    
    if ($appointmentModel->updateStatus($id, $novo_estado)) {
        // Redirect to same page to prevent resubmission
        header("Location: barbeiro_agenda.php?barber=" . urlencode($barberName));
        exit();
    } else {
        $error = "Erro ao atualizar estado.";
    }
}

// Fetch Data
$marcacoes_futuras = $appointmentModel->getFutureByBarber($barberName);
$marcacoes_passadas_pendentes = $appointmentModel->getPastPendingByBarber($barberName);

// Determine CSS file based on barber (if they have specific styles, otherwise share)
// The original files had specific CSS: barbeiro1.css. Assuming barbeiro2 used the same or similar?
// Checked barbeiro2.php (Step 209): linked to `../assets/css/barbeiro1.css`. So they share CSS.
$cssFile = '../assets/css/barbeiro1.css';
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($barberName) ?> - Marcações</title>
    <link rel="stylesheet" href="<?= $cssFile ?>">
</head>
<body>
    <?php include '../includes/navbarLateral.php'; ?>
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
                                <form id="form-<?= $row['id'] ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="estado" value="">
                                    <!-- Using simple buttons that submit the form via JS or changing type to submit with value -->
                                    <!-- Replicating original JS modal logic or simplifying? -->
                                    <!-- Original used abrirModal(). Let's keep the JS hook if possible or simplify. -->
                                    <!-- To keep it simple and robust, let's use standard confirm for now or keep the modal if JS is present. -->
                                    <!-- The original JS `barbeirosMarc.js` is included. I should check if it relies on specific IDs. -->
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
                                <form id="form-<?= $row['id'] ?>" method="POST" style="display:inline;">
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

        <!-- Modal (Kept from original) -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="icon"></div>
                <p id="modal-message"></p>
                <button id="confirm-button" onclick="confirmarAcao()">Confirmar</button>
                <button onclick="fecharModal()">Cancelar</button>
            </div>
        </div>
    </div>
    <script src="../assets/js/barbeirosMarc.js"></script>
    <!-- We need to ensure the JS knows where to submit. The original JS likely manipulated a form with id='form-ID'. -->
    <!-- I need to ensure the forms have those IDs. -->
    <script>
        // Helper to ensure the form submission works with the existing JS logic if it relies on specific IDs
        // Original JS: document.getElementById('form-' + id).submit();
        // So I need to add id='form-<?= $row['id'] ?>' to the forms.
    </script>
</body>
</html>
