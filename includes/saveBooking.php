<?php
date_default_timezone_set('Europe/Lisbon');
include('./db.php');
include('./bookingUtils.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service = filter_var($_POST['service'] ?? '', FILTER_SANITIZE_STRING);
    $barber = filter_var($_POST['barber'] ?? '', FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'] ?? '', FILTER_SANITIZE_STRING);
    $time = filter_var($_POST['time'] ?? '', FILTER_SANITIZE_STRING);
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
    $phone = filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);

    error_log("Dados recebidos: service=$service, barber=$barber, date=$date, time=$time, name=$name, phone=$phone, email=$email");

    $errors = [];
    if (empty($service)) $errors[] = 'O campo "Serviço" é obrigatório.';
    if (empty($barber)) $errors[] = 'O campo "Barbeiro" é obrigatório.';
    if (empty($date)) $errors[] = 'O campo "Data" é obrigatório.';
    if (empty($time)) $errors[] = 'O campo "Horário" é obrigatório.';
    if (empty($name)) $errors[] = 'O campo "Nome" é obrigatório.';
    if (empty($phone)) $errors[] = 'O campo "Telefone" é obrigatório.';
    if (empty($email)) $errors[] = 'O campo "Email" é obrigatório.';
    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) $errors[] = 'O nome deve conter apenas letras e espaços.';
    if (strlen($phone) !== 9 || !is_numeric($phone)) $errors[] = 'O telemóvel deve ter 9 dígitos.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'O e-mail é inválido.';

    if (!empty($errors)) {
        $errorMessage = implode(' ', $errors);
        error_log(date('[Y-m-d H:i:s] ') . "Erros de validação: " . $errorMessage);
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }

    // Converter data para o formato do banco
    $dateParts = explode('-', $date);
    if (count($dateParts) !== 3) {
        $errorMessage = 'O formato de data é inválido.';
        error_log(date('[Y-m-d H:i:s] ') . "Erro de formato de data: $date");
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }
    $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

    // Verificar disponibilidade do horário
    $bookingUtils = new BookingUtils($pdo);
    if (!$bookingUtils->isTimeSlotAvailable($barber, $date, $time)) {
        $errorMessage = 'Este horário já foi reservado. Por favor, escolha outro.';
        error_log(date('[Y-m-d H:i:s] ') . "Erro: $errorMessage - barber=$barber, date=$formattedDate, time=$time");
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }

    // Inserir a marcação
    $sql = "INSERT INTO marcacoes (nome_utilizador, telefone_utilizador, email_utilizador, barbeiro, servico, data_marcacao, horario_marcacao, estado, criado_em, atualizado_em)
            VALUES (:name, :phone, :email, :barber, :service, :date, :time, :status, :created_at, :updated_at)";
    $stmt = $pdo->prepare($sql);

    $status = 'marcada';
    $created_at = date('Y-m-d H:i:s');
    $updated_at = $created_at;

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':barber', $barber);
    $stmt->bindParam(':service', $service);
    $stmt->bindParam(':date', $formattedDate);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->bindParam(':updated_at', $updated_at);

    if ($stmt->execute()) {
        error_log(date('[Y-m-d H:i:s] ') . "Reserva realizada com sucesso para: service=$service, barber=$barber, date=$formattedDate, time=$time, name=$name, phone=$phone, email=$email");

        // Enviar email
        $emailData = [
            'name' => $name,
            'email' => $email,
            'date' => $date,
            'time' => $time,
            'barber' => $barber,
            'service' => $service
        ];
        $ch = curl_init('http://localhost/Barbearia/includes/sendBookingEmail.php');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($emailData),
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $response = json_decode(curl_exec($ch), true);
        $emailSent = $response['success'] ?? false;
        $emailError = $response['message'] ?? 'Erro desconhecido ao enviar email';
        curl_close($ch);

        if (!$emailSent) {
            error_log(date('[Y-m-d H:i:s] ') . "Erro ao enviar email: $emailError");
        }

        $redirectUrl = "marcacoes.php?date=" . urlencode($date) . "&time=" . urlencode($time) . "&barber=" . urlencode($barber) . "&name=" . urlencode($name) . "&service=" . urlencode($service);
        echo "<script>window.location.href = '$redirectUrl';</script>";
        exit;
    } else {
        $errorMessage = 'Ocorreu um erro ao guardar a reserva na base de dados: ' . implode(' ', $stmt->errorInfo());
        error_log(date('[Y-m-d H:i:s] ') . "Erro ao guardar a reserva: " . implode(' ', $stmt->errorInfo()));
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }
} else {
    $errorMessage = 'Método de requisição inválido.';
    error_log(date('[Y-m-d H:i:s] ') . "Método de requisição inválido: " . $_SERVER["REQUEST_METHOD"]);
    echo json_encode(['success' => false, 'message' => $errorMessage]);
}