<?php
require_once 'conexion.php';

$comentarios = [
    1 => [
        ['user_name' => 'Maria', 'rating' => 5.0, 'comentario' => 'Increible apartamento! La ubicacion es perfecta, a 2 minutos de la playa. Todo muy limpio y equipado. Lo recomiendo completamente.'],
        ['user_name' => 'Carlos', 'rating' => 4.5, 'comentario' => 'Muy buena experiencia. El apartamento tiene de todo y el anfitrion fue muy amable. Volveriamos sin duda.'],
        ['user_name' => 'Laura', 'rating' => 4.8, 'comentario' => 'Nos encanto la decoracion y la luz natural. Perfecto para una pareja. Barrio autentico y cerca de todo.']
    ],
    2 => [
        ['user_name' => 'Pedro', 'rating' => 4.0, 'comentario' => 'Loft moderno y funcional. Perfecto para viajes de negocios. La cocina esta muy bien equipada.'],
        ['user_name' => 'Ana', 'rating' => 4.7, 'comentario' => 'Muy centrico y tranquilo a la vez. Ideal para explorar Barcelona a pie. Lo recomiendo.']
    ],
    3 => [
        ['user_name' => 'Diego', 'rating' => 5.0, 'comentario' => 'Espectacular! Las vistas al mar son increibles. Jacuzzi privado y terraza perfecta. Vale cada euro.'],
        ['user_name' => 'Sofia', 'rating' => 4.9, 'comentario' => 'Celebramos nuestro aniversario aqui y fue perfecto. Servicio de lujo y atencion personalizada.']
    ],
    4 => [
        ['user_name' => 'Javier', 'rating' => 4.6, 'comentario' => 'Apartamento con caracter en el mejor barrio de Barcelona. Cerca del Parc de la Ciutadella y del Born.'],
        ['user_name' => 'Elena', 'rating' => 4.3, 'comentario' => 'Bonito y acogedor. Perfecto para explorar la ciudad. Lo recommendare a amigos que visitan Barcelona.']
    ],
    5 => [
        ['user_name' => 'Marco', 'rating' => 4.2, 'comentario' => 'Estudio compacto pero muy bien aprovechamiento. Ideal para surfistas por la cercanía a la playa.'],
        ['user_name' => 'Lucia', 'rating' => 4.5, 'comentario' => 'Zona tranquila y vue.la al mar. Perfecto para descansar despues de un dia de turismo.']
    ],
    6 => [
        ['user_name' => 'Ricardo', 'rating' => 4.8, 'comentario' => 'Piso barcelones autentico con mucho encanto. Techos altos y molduras originales preciosas'],
        ['user_name' => 'Patricia', 'rating' => 4.4, 'comentario' => 'Nos encanto el ambiente bohemio de Gràcia. Hay bares y restaurantes muy nice.']
    ]
];

foreach ($comentarios as $apt_id => $comments) {
    foreach ($comments as $c) {
        $user_name = $c['user_name'];
        $rating = $c['rating'];
        $comentario = $c['comentario'];
        $sql = "INSERT INTO comentarios (apartamento_id, user_name, rating, comentario) VALUES ($apt_id, '$user_name', $rating, '$comentario')";
        $conn->query($sql);
    }
}

echo "Comentarios insertados";
?>