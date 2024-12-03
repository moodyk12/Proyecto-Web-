<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer{
    function email($email, $asunto, $cuerpo){

        require_once './config/config.php';
        require './phpmailer/src/PHPMailer.php';
        require './phpmailer/src/SMTP.php';
        require './phpmailer/src/Exception.php';

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                     // Habilita salida detallada de errores
            $mail->isSMTP();                                           // Usa SMTP
            $mail->Host       = MAIL_HOST;                              // Servidor SMTP correcto
            $mail->SMTPAuth   = true;                                  // Habilita autenticación SMTP
            $mail->Username   = MAIL_USER;                              // Tu correo
            $mail->Password   = MAIL_PASS;                              // Contraseña de aplicación de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Usa TLS
            $mail->Port       = MAIL_PORT;                              // Puerto correcto para STARTTLS

            // Configuración del correo
            $mail->setFrom(MAIL_USER, 'Tienda Bunny Vibes');  // Remitente
            $mail->addAddress($email);   // Destinatario

            // Contenido
            $mail->isHTML(true);                                       // Formato HTML
            $mail->Subject = $asunto;

            $mail->Body    = mb_convert_encoding($cuerpo, 'UTF-8', 'ISO-8859-1');

            // Enviar correo
            if($mail->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    }
}
