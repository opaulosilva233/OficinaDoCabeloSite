php
<?php
date_default_timezone_set('Europe/Lisbon');
/**
 * Este ficheiro tem como objetivo enviar um email de confirmação para o cliente
 * Este ficheiro é responsável por enviar um email de confirmação de marcação para o cliente.
 * O email é enviado usando a função mail() do PHP.
 */

// Verifica se o método da requisição é POST. Este bloco só será executado se a requisição for POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recebe os dados da marcação enviados via POST. Os dados do formulário são recebidos através do array $_POST.
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $barber = $_POST['barber'];
    $service = $_POST['service'];
    
     // Log dos dados do email com timestamp.
    error_log(date('[Y-m-d H:i:s] ') . "Tentando enviar email para: $email, nome: $name, data: $date, hora: $time, barbeiro: $barber, servico: $service");
    
    // Define o destinatário do email. O email será enviado para o email do cliente.
    $to = $email;
    // Define o assunto do email. Este será o assunto que o cliente verá no seu email.
    $subject = "Confirmação da sua Marcação";
    // Define o corpo da mensagem do email, em formato HTML.
    $message = "
    <html>
    <head>
        <title>Confirmação da sua Marcação</title>
    </head>
    <body>
    <!-- Corpo do email com os detalhes da marcação -->
        <p>Olá $name,</p>
        <p>A sua marcação foi confirmada com sucesso!</p>
        <p>Aqui estão os detalhes da sua marcação:</p>
        <ul>
            <li><strong>Data:</strong> $date</li>
            <li><strong>Hora:</strong> $time</li>
            <li><strong>Barbeiro:</strong> $barber</li>
            <li><strong>Serviço:</strong> $service</li>
        </ul>
        <p>Obrigado por escolher a nossa barbearia.</p>
    </body>
    </html>";
    
    // Define os cabeçalhos do email. Estes cabeçalhos informam o tipo de conteúdo e o remetente do email.
    // MIME-Version: Indica a versão do MIME utilizada.
    $headers = "MIME-Version: 1.0" . "\r\n";
    // Content-type: Define o tipo de conteúdo como HTML e o charset como UTF-8.
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    // From: Define o remetente do email. Substitua <your-email@example.com> pelo seu email.
    $headers .= 'From: <your-email@example.com>' . "\r\n"; 

    if (mail($to, $subject, $message, $headers)) {
        error_log(date('[Y-m-d H:i:s] ') . "Email enviado com sucesso para: $email");
        echo json_encode(['success' => true, 'message' => 'Email enviado com sucesso para ' . $email]);
    } else {
         $errorMessage = error_get_last()['message'];
        error_log(date('[Y-m-d H:i:s] ') . "Erro ao enviar o email para: $email. Erro: $errorMessage");
        echo json_encode(['success' => false, 'message' => "Erro ao enviar o email para " . $email . ". Detalhe: " . $errorMessage]);
    }
}
?>