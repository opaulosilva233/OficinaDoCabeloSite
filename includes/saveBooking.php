<?php
date_default_timezone_set('Europe/Lisbon');
// Incluir o ficheiro de ligação à base de dados. Este arquivo estabelece a ligação com a base de dados MySQL.
include('./db.php'); // Caminho atualizado com './'

// Verificar se os dados foram enviados via POST. O código dentro deste if só será executado se a requisição for POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receber os dados do formulário. Os dados enviados pelo formulário são recebidos através do array $_POST.
    // O operador de coalescência nula (??) é usado para definir valores padrão se os campos não forem definidos.
    $service = isset($_POST['service']) ? $_POST['service'] : null;
    $barber = isset($_POST['barber']) ? $_POST['barber'] : null;
    $date = isset($_POST['date']) ? $_POST['date'] : null;

    
    
    // O operador ternário (condição ? valor_se_verdadeiro : valor_se_falso) é usado para simplificar a lógica.
    $time = isset($_POST['time']) ? $_POST['time'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $phone = isset($_POST['phone']) ? $_POST['phone'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;

    $emailSent = true;
    $emailError = "";
    // Registo dos dados recebidos para fins de depuração com timestamp.
    error_log("Dados recebidos: service=$service, barber=$barber, date=$date, time=$time, name=$name, phone=$phone, email=$email");

    // Inicializar um array para armazenar mensagens de erro. Se houver erros de validação, eles serão armazenados aqui.
    $errors = []; 

    // Validação de cada campo individualmente
    if (empty($service)) {
        $errors[] = 'O campo "Serviço" é obrigatório.';
    }
    if (empty($barber)) {
        $errors[] = 'O campo "Barbeiro" é obrigatório.';
    }
    if (empty($date)) {
        $errors[] = 'O campo "Data" é obrigatório.';
    }
    if (empty($time)) {
        $errors[] = 'O campo "Horário" é obrigatório.';
    }
    if (empty($name)) {
        $errors[] = 'O campo "Nome" é obrigatório.';
    }
    if (empty($phone)) {
        $errors[] = 'O campo "Telefone" é obrigatório.';
    }
    if (empty($email)) {
        $errors[] = 'O campo "Email" é obrigatório.';
    }

    // Verificar se há erros de validação
    if (!empty($errors)) {
        $errorMessage = implode(' ', $errors);
        error_log(date('[Y-m-d H:i:s] ') . "Erros de validação: " . $errorMessage);
        echo json_encode(['success' => false, 'message' => $errorMessage]); // Enviar os erros de validação em formato JSON.
        exit;
    }

    // Converter a data de "DD-MM-YYYY" para "YYYY-MM-DD". Este bloco converte a data recebida para o formato YYYY-MM-DD para ser usada na base de dados.
    if ($date) {
        $dateParts = explode('-', $date); // Divide a data em dia, mês e ano.
        if (count($dateParts) == 3) {
            // A data está no formato "DD-MM-YYYY", então podemos converter
             // Reorganizar a data para o formato "YYYY-MM-DD".
            $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0]; // "YYYY-MM-DD"
        } else {
            $errorMessage = 'O formato de data é inválido. Por favor utilize o formato DD-MM-YYYY.';
            error_log(date('[Y-m-d H:i:s] ') . "Erro de formato de data: $date");
            echo json_encode(['success' => false, 'message' => $errorMessage]);
            exit;
        }
    }
    // Verificar se já existe uma marcação com o mesmo email, data, hora e barbeiro.
    $checkSql = "SELECT * FROM marcacoes WHERE email_utilizador = :email AND data_marcacao = :date AND horario_marcacao = :time AND barbeiro = :barber";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->bindParam(':date', $formattedDate);
    $checkStmt->bindParam(':time', $time);
    $checkStmt->bindParam(':barber', $barber);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() > 0) {
        $errorMessage = 'Já existe uma marcação com o mesmo email, data, hora e barbeiro.';
        error_log(date('[Y-m-d H:i:s] ') . "Erro: $errorMessage - email=$email, date=$formattedDate, time=$time, barber=$barber");
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }
    
    // Preparar a consulta SQL com prepared statements. Isso ajuda a evitar ataques de SQL injection.
    $sql = "INSERT INTO marcacoes (nome_utilizador, telefone_utilizador, email_utilizador, barbeiro, servico, data_marcacao, horario_marcacao, estado, criado_em, atualizado_em)
            VALUES (:name, :phone, :email, :barber, :service, :date, :time, :status, :created_at, :updated_at)";
    $stmt = $pdo->prepare($sql);

    // Verificar se a consulta foi preparada corretamente
    if ($stmt === false) {
        $errorMessage = 'Ocorreu um erro ao preparar a consulta para a base de dados.';
        error_log(date('[Y-m-d H:i:s] ') ."Erro ao preparar a consulta SQL: " . implode(' ', $pdo->errorInfo()));
        echo json_encode(['success' => false, 'message' => $errorMessage]);
        exit;
    }

    // Definir os valores para os parâmetros.
    $status = 'marcada'; // Estado inicial da marcação.
    $created_at = date('Y-m-d H:i:s'); // Data e hora de criação.
    $updated_at = $created_at; // Data e hora de atualização (inicialmente igual à criação).

    // Vincular os parâmetros. Isto associa os valores recebidos aos parâmetros da consulta SQL.
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

    // Executar a consulta. Aqui a consulta é executada com os valores fornecidos.
    if ($stmt->execute()) {
        error_log(date('[Y-m-d H:i:s] ')."Reserva realizada com sucesso para: service=$service, barber=$barber, date=$formattedDate, time=$time, name=$name, phone=$phone, email=$email");
        
        //Send email to the client. Esta secção envia um email de confirmação ao cliente.
        $emailData = array(
            'name' => $name,
            'email' => $email,
            'date' => $date,
            'time' => $time,
            'barber' => $barber,
            'service' => $service
        );
        //Inicializar cURL para enviar o email.
        $ch = curl_init('http://localhost/Barbearia/includes/sendBookingEmail.php');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($emailData),
            CURLOPT_RETURNTRANSFER => true,
        ]);

        // Executa o pedido cURL
        $response = json_decode(curl_exec($ch), true);
        $emailSent = $response['success'];
        $emailError = $response['message'];
        curl_close($ch); // Fechar a sessão cURL.
        
        // Construct the URL with parameters. Esta secção constrói a URL para a qual o cliente será redirecionado.
        $redirectUrl = "marcacoes.php?date=" . urlencode($date) . "&time=" . urlencode($time) . "&barber=" . urlencode($barber) . "&name=" . urlencode($name) . "&service=" . urlencode($service);
        
        // Send JavaScript to redirect. Isto redireciona o usuário para a página de confirmação.
        echo "<script>";
        echo "window.location.href = '" . $redirectUrl . "';";
        echo "</script>";
        exit;

    } else {
        $errorMessage = 'Ocorreu um erro ao guardar a reserva na base de dados: ' . implode(' ', $stmt->errorInfo());
        error_log(date('[Y-m-d H:i:s] ')."Erro ao guardar a reserva: " . implode(' ', $stmt->errorInfo()));
        echo json_encode(['success' => false, 'message' => $errorMessage]);
    }
} else {
    $errorMessage = 'Método de requisição inválido. Apenas requisições POST são permitidas.';
    error_log(date('[Y-m-d H:i:s] ')."Método de requisição inválido: " . $_SERVER["REQUEST_METHOD"]);
    echo json_encode(['success' => false, 'message' => $errorMessage]);
} // Fim da condição para verificar o método da requisição.
?>