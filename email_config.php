<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

function enviarEmail($email_destino, $nombre_destino, $apartamento, $barrio, $checkin, $checkout, $noches, $total) {
    $mail = new PHPMailer(true);
    
    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tuemail@gmail.com'; // CAMBIA ESTO
        $mail->Password   = 'tucontraseña';       // CAMBIA ESTO (usa App Password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        
        $mail->setFrom('noreply@apbarcelona.com', 'ApBarcelona');
        $mail->addAddress($email_destino, $nombre_destino);
        
        $mail->isHTML(false);
        $mail->Subject = 'Confirmación de tu reserva en ApBarcelona';
        
        $mensaje = "Hola $nombre_destino,\n\n";
        $mensaje .= "Gracias por tu reserva en ApBarcelona.\n\n";
        $mensaje .= "Detalles de tu reserva:\n";
        $mensaje .= "- Apartamento: $apartamento\n";
        $mensaje .= "- Ubicación: $barrio\n";
        $mensaje .= "- Fecha de entrada: $checkin\n";
        $mensaje .= "- Fecha de salida: $checkout\n";
        $mensaje .= "- Número de noches: $noches\n";
        $mensaje .= "- Total: €$total\n\n";
        $mensaje .= "Te esperamos en Barcelona.\n\n";
        $mensaje .= "Saludos,\n";
        $mensaje .= "El equipo de ApBarcelona\n";
        
        $mail->Body = $mensaje;
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>