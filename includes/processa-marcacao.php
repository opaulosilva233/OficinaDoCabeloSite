<?php
session_start();
require_once 'db.php'; // Certifique-se de que a conexão ao banco está correta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se todos os campos necessários foram preenchidos
    if (!empty($_POST['service']) && !empty($_POST['name']) && !empty($_POST['phone']) && 
        !empty($_POST['email']) && !empty($_POST['barber']) && !empty($_POST['date']) && !empty($_POST['time'])) {
        
        // Captura os valores do formulário
        $service = htmlspecialchars($_POST['service']);
        $name = htmlspecialchars($_POST['name']);
        $phone = htmlspecialchars($_POST['phone']);
        $email = htmlspecialchars($_POST['email']);
        $barber = htmlspecialchars($_POST['barber']);
        $date = htmlspecialchars($_POST['date']);
        $time = htmlspecialchars($_POST['time']);
        
        // Verificar disponibilidade do horário
        try {
            // Consulta para verificar se já existe uma marcação com o mesmo barbeiro, data e horário
            $sql = "SELECT COUNT(*) FROM marcacoes WHERE barbeiro = :barber AND data = :date AND horario = :time";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':barber' => $barber,
                ':date' => $date,
                ':time' => $time
            ]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                // Se o horário já estiver ocupado, exibe uma mensagem
                echo "Esse horário já está ocupado. Por favor, escolha outro.";
                exit;
            }

            // Se o horário estiver disponível, insere a marcação
            $sql = "INSERT INTO marcacoes (servico, nome, telefone, email, barbeiro, data, horario, estado) 
                    VALUES (:service, :name, :phone, :email, :barber, :date, :time, 'marcado')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':service' => $service,
                ':name' => $name,
                ':phone' => $phone,
                ':email' => $email,
                ':barber' => $barber,
                ':date' => $date,
                ':time' => $time
            ]);

            // Atribui o ID da marcação à sessão
            $_SESSION['marcacao_id'] = $pdo->lastInsertId();
            $_SESSION['servico'] = $service;
            $_SESSION['nome'] = $name;
            $_SESSION['telemovel'] = $phone;
            $_SESSION['email'] = $email;
            $_SESSION['barbeiro'] = $barber;
            $_SESSION['data'] = $date;
            $_SESSION['horario'] = $time;

            // Redireciona para a página de confirmação
            header("Location: /confirmacao.php");
            exit;

        } catch (PDOException $e) {
            echo "Erro ao inserir dados: " . $e->getMessage();
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>
