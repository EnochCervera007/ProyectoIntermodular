<?php
function enviarEmail($email_destino, $nombre_destino, $apartamento, $barrio, $checkin, $checkout, $noches, $total) {
    $asunto = "Confirmación de tu reserva en ApBarcelona";
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
    
    $headers = "From: noreply@apbarcelona.com\r\n";
    $headers .= "Reply-To: noreply@apbarcelona.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    return mail($email_destino, $asunto, $mensaje, $headers);
}
?>