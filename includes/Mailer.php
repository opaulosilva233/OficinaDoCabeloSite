<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer Manually (since we don't have composer autoload)
require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

class Mailer {
    
    // CONFIGURAÇÃO SMTP (Preencher com dados reais)
    private const SMTP_HOST = 'smtp.gmail.com'; 
    private const SMTP_USER = 'seu_email@gmail.com'; // ALTERAR AQUI
    private const SMTP_PASS = 'sua_senha_aplicacao'; // ALTERAR AQUI
    private const SMTP_PORT = 587;
    private const FROM_EMAIL = 'no-reply@oficinadocabelo.com';
    private const FROM_NAME = 'Oficina do Cabelo';

    public static function sendConfirmation($toEmail, $clientName, $appointmentData) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = self::SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = self::SMTP_USER;
            $mail->Password   = self::SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = self::SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            // Recipients
            $mail->setFrom(self::FROM_EMAIL, self::FROM_NAME);
            $mail->addAddress($toEmail, $clientName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Confirmação de Agendamento - Oficina do Cabelo';
            
            // Format Date and Time
            $dateFormatted = date('d/m/Y', strtotime($appointmentData['date']));
            $timeFormatted = date('H:i', strtotime($appointmentData['time']));

            // Email Body
            $body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                <h2 style='color: #d4a373; text-align: center;'>Agendamento Confirmado! ✅</h2>
                <p>Olá <strong>$clientName</strong>,</p>
                <p>Obrigado por agendar com a Oficina do Cabelo. Aqui estão os detalhes do seu corte:</p>
                
                <table style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
                    <tr style='border-bottom: 1px solid #eee;'>
                        <td style='padding: 10px; font-weight: bold;'>Serviço:</td>
                        <td style='padding: 10px;'>{$appointmentData['service']}</td>
                    </tr>
                    <tr style='border-bottom: 1px solid #eee;'>
                        <td style='padding: 10px; font-weight: bold;'>Barbeiro:</td>
                        <td style='padding: 10px;'>{$appointmentData['barber']}</td>
                    </tr>
                    <tr style='border-bottom: 1px solid #eee;'>
                        <td style='padding: 10px; font-weight: bold;'>Data:</td>
                        <td style='padding: 10px;'>$dateFormatted</td>
                    </tr>
                    <tr style='border-bottom: 1px solid #eee;'>
                        <td style='padding: 10px; font-weight: bold;'>Horário:</td>
                        <td style='padding: 10px;'>$timeFormatted</td>
                    </tr>
                </table>

                <div style='margin-top: 30px; text-align: center; color: #777; font-size: 0.9rem;'>
                    <p>Se precisar de alterar, por favor contacte-nos.</p>
                    <p>Oficina do Cabelo | Ermesinde</p>
                </div>
            </div>";
            
            $mail->Body = $body;
            $mail->AltBody = "Olá $clientName. O seu agendamento para {$appointmentData['date']} às {$appointmentData['time']} foi confirmado. Serviço: {$appointmentData['service']}.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error but don't crash
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
?>
