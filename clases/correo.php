<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                     // Habilita salida detallada de errores
    $mail->isSMTP();                                           // Usa SMTP
    $mail->Host       = 'smtp.gmail.com';                      // Servidor SMTP correcto
    $mail->SMTPAuth   = true;                                  // Habilita autenticación SMTP
    $mail->Username   = 'moodykarla497@gmail.com';              // Tu correo
    $mail->Password   = 'vvwm kudr jfjc stzl';         // Contraseña de aplicación de Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Usa TLS
    $mail->Port       = 587;                                   // Puerto correcto para STARTTLS

    // Configuración del correo
    $mail->setFrom('moodykarla497@gmail.com', 'Tienda Moody');  // Remitente
    $mail->addAddress('karlamoody12@gmail.com', 'Joe User');   // Destinatario

    // Contenido
    $mail->isHTML(true);                                       // Formato HTML
    $mail->Subject = 'Detalle de su compra';
    $cuerpo = '<h4>Gracias por su compra </h4>';
    $cuerpo .= '<p>El Id de su compra es <b>' . $id_transaccion . '</b></>';



    $mail->Body    = utf8_encode($cuerpo);
    $mail->AltBody = 'Prueba de venta para ver si sirve (texto sin formato HTML)';

    // Enviar correo
    $mail->send();
    echo 'Correo enviado';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
