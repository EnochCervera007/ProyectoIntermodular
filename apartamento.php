<?php
session_start();
require_once 'conexion.php';

$logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';

$id = $_GET['id'] ?? 1;

$apartamentos = [
    1 => [
        'titulo' => 'Piso Luminoso Barceloneta',
        'barrio' => 'Barceloneta',
        'precio' => 95,
        'habitaciones' => 2,
        'banos' => 1,
        'personas' => 4,
        'rating' => 4.9,
        'resenas' => 84,
        'descripcion' => 'Precioso apartamento situado en el corazón de la Barceloneta, a solo 2 minutos de la playa. Totalmente reformado con cocina equipada, lavadora y WiFi de alta velocidad. Ideal para familias o parejas que buscan una experiencia auténtica en Barcelona.',
        'imagenes' => [
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800'
        ],
        'categoria' => 'playa'
    ],
    2 => [
        'titulo' => 'Loft Moderno Eixample',
        'barrio' => 'Eixample Dret',
        'precio' => 120,
        'habitaciones' => 1,
        'banos' => 1,
        'personas' => 2,
        'rating' => 4.7,
        'resenas' => 61,
        'descripcion' => 'Luminoso loft en el elegante barrio del Eixample. Decoración moderna yminimalista. Cerca de la Sagrada Familia y Passeig de Gràcia. Perfecto para parejas o viajeros solos.',
        'imagenes' => [
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800'
        ],
        'categoria' => 'centro'
    ],
    3 => [
        'titulo' => 'Penthouse con Vistas al Mar',
        'barrio' => 'Port Olímpic',
        'precio' => 280,
        'habitaciones' => 3,
        'banos' => 2,
        'personas' => 6,
        'rating' => 5.0,
        'resenas' => 112,
        'descripcion' => 'Espectacular penthouse con vistas panorámicas al mar. Terraza privada de 30m², jacuzzi y cocina gourmet. La experiencia definitiva en lujo barcelonés.',
        'imagenes' => [
            'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=800',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
            'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800'
        ],
        'categoria' => 'lujo'
    ],
    4 => [
        'titulo' => 'Apartamento El Born',
        'barrio' => 'El Born',
        'precio' => 88,
        'habitaciones' => 2,
        'banos' => 1,
        'personas' => 3,
        'rating' => 4.8,
        'resenas' => 48,
        'descripcion' => 'Encantador apartamento en el barrio más bohemio de Barcelona. Cerca del Parc de la Ciutadella y la Barceloneta. Perfecto para explorar la ciudad.',
        'imagenes' => [
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800'
        ],
        'categoria' => 'centro'
    ],
    5 => [
        'titulo' => 'Estudio Vista Mar Poblenou',
        'barrio' => 'Poblenou',
        'precio' => 74,
        'habitaciones' => 1,
        'banos' => 1,
        'personas' => 2,
        'rating' => 4.6,
        'resenas' => 37,
        'descripcion' => 'Acogedor estudio con vistas al mar. Perfecto para surferos y amantes de la playa. Zona tranquila a 10 minutos del centro.',
        'imagenes' => [
            'https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800'
        ],
        'categoria' => 'playa'
    ],
    6 => [
        'titulo' => 'Piso Clásico Gràcia',
        'barrio' => 'Gràcia',
        'precio' => 105,
        'habitaciones' => 2,
        'banos' => 2,
        'personas' => 4,
        'rating' => 4.9,
        'resenas' => 73,
        'descripcion' => 'Auténtico piso barcelonés en el barrio de Gràcia. Techos altos, molduras originales y ambiente bohemio. Ideal para grupos.',
        'imagenes' => [
            'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800'
        ],
        'categoria' => 'centro'
    ]
];

$apt = $apartamentos[$id] ?? $apartamentos[1];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($apt['titulo']); ?> – ApBarcelona</title>
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    body { background: #fff; }
    .apt-page { padding: 100px 20px 60px; }
    .apt-container { max-width: 1100px; margin: 0 auto; }
    .apt-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 12px 24px; background: transparent; border: 1px solid #ddd;
      border-radius: 8px; color: #333; font-size: 0.9rem; text-decoration: none;
      transition: all 0.2s;
    }
    .btn-back:hover { background: #f5f5f5; color: #000; border-color: #ccc; }
    .apt-gallery { display: grid; grid-template-columns: 2fr 1fr; gap: 8px; margin-bottom: 32px; border-radius: 16px; overflow: hidden; }
    .apt-main-img { width: 100%; height: 400px; object-fit: cover; }
    .apt-side-imgs { display: flex; flex-direction: column; gap: 8px; }
    .apt-side-imgs img { width: 100%; height: 196px; object-fit: cover; }
    .apt-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #000; margin-bottom: 8px; }
    .apt-location { color: #666; font-size: 1rem; margin-bottom: 24px; }
    .apt-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 32px; }
    .apt-info { }
    .apt-desc-title { color: #000; font-size: 1.2rem; margin-bottom: 12px; }
    .apt-desc { color: #444; line-height: 1.7; margin-bottom: 32px; }
    .apt-features { display: flex; gap: 24px; margin-bottom: 32px; }
    .apt-feature { display: flex; align-items: center; gap: 8px; color: #444; }
    .apt-feature span { font-size: 1.1rem; }
    .apt-rating { display: flex; align-items: center; gap: 8px; color: #000; font-size: 1.1rem; margin-bottom: 32px; }
    .apt-rating span { color: #666; font-size: 0.9rem; }
    .booking-card {
      background: #f8f8f8; border: 1px solid #ddd; border-radius: 16px; padding: 28px; position: sticky; top: 100px;
    }
    .booking-price { display: flex; align-items: baseline; gap: 4px; margin-bottom: 20px; }
    .booking-price .price { font-size: 2rem; font-weight: 700; color: #000; }
    .booking-price .per { color: #666; }
    .booking-dates { margin-bottom: 20px; }
    .booking-dates label { display: block; color: #666; font-size: 0.85rem; margin-bottom: 8px; }
    .booking-dates input {
      width: 100%; padding: 14px; background: #fff; border: 1px solid #ddd;
      border-radius: 8px; color: #000; font-size: 1rem;
    }
    .booking-dates input[type="date"] { color-scheme: light; }
    .booking-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px; }
    .booking-summary {
      background: #fff; border-radius: 8px; padding: 16px; margin-bottom: 20px;
    }
    .booking-summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; color: #666; }
    .booking-summary-row:last-child { margin-bottom: 0; padding-top: 8px; border-top: 1px solid #eee; color: #000; font-weight: 600; }
    .btn-book {
      width: 100%; padding: 16px; background: #C8102E;
      border: none; border-radius: 8px; color: #fff; font-size: 1rem; font-weight: 600;
      cursor: pointer; transition: transform 0.2s;
    }
    .btn-book:hover { transform: translateY(-2px); }
  </style>
</head>
<body>
  <div class="apt-page">
    <div class="apt-container">
      <div class="apt-header">
        <a href="index.php" class="btn-back">← Volver</a>
      </div>
      
      <div class="apt-gallery">
        <img src="<?php echo $apt['imagenes'][0]; ?>" alt="<?php echo htmlspecialchars($apt['titulo']); ?>" class="apt-main-img">
        <div class="apt-side-imgs">
          <img src="<?php echo $apt['imagenes'][1]; ?>" alt="Imagen 2">
          <img src="<?php echo $apt['imagenes'][2]; ?>" alt="Imagen 3">
        </div>
      </div>
      
      <h1 class="apt-title"><?php echo htmlspecialchars($apt['titulo']); ?></h1>
      <p class="apt-location">📍 <?php echo htmlspecialchars($apt['barrio']); ?>, Barcelona</p>
      
      <div class="apt-grid">
        <div class="apt-info">
          <h3 class="apt-desc-title">Descripción</h3>
          <p class="apt-desc"><?php echo htmlspecialchars($apt['descripcion']); ?></p>
          
          <div class="apt-features">
            <div class="apt-feature"><span>🛏</span> <?php echo $apt['habitaciones']; ?> habitaciones</div>
            <div class="apt-feature"><span>🚿</span> <?php echo $apt['banos']; ?> baño<?php echo $apt['banos'] > 1 ? 's' : ''; ?></div>
            <div class="apt-feature"><span>👥</span> Hasta <?php echo $apt['personas']; ?> personas</div>
          </div>
          
          <div class="apt-rating">
            ★ <?php echo $apt['rating']; ?> <span>(<?php echo $apt['resenas']; ?> reseñas)</span>
          </div>
        </div>
        
        <div class="booking-card">
          <div class="booking-price">
            <span class="price">€<?php echo $apt['precio']; ?></span>
            <span class="per">/noche</span>
          </div>
          
          <form method="POST" action="reservar.php" id="bookingForm">
            <input type="hidden" name="apartamento_id" value="<?php echo $id; ?>">
            <div class="booking-dates">
              <label>Entrada</label>
              <input type="date" id="checkin" name="checkin" required min="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="booking-dates">
              <label>Salida</label>
              <input type="date" id="checkout" name="checkout" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
            </div>
            
            <div class="booking-summary" id="bookingSummary" style="display:none;">
              <div class="booking-summary-row">
                <span>€<?php echo $apt['precio']; ?> x <span id="nights">0</span> noches</span>
                <span id="subtotal">€0</span>
              </div>
              <div class="booking-summary-row">
                <span>Servicio</span>
                <span>€0</span>
              </div>
              <div class="booking-summary-row">
                <span>Total</span>
                <span id="total">€0</span>
              </div>
            </div>
            
            <button type="submit" class="btn-book">Reservar ahora</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    const precio = <?php echo $apt['precio']; ?>;
    const checkin = document.getElementById('checkin');
    const checkout = document.getElementById('checkout');
    const summary = document.getElementById('bookingSummary');
    const nightsSpan = document.getElementById('nights');
    const subtotalSpan = document.getElementById('subtotal');
    const totalSpan = document.getElementById('total');
    
    function calcularTotal() {
      if (checkin.value && checkout.value) {
        const d1 = new Date(checkin.value);
        const d2 = new Date(checkout.value);
        const noches = Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24));
        
        if (noches > 0) {
          const subtotal = precio * noches;
          const total = subtotal;
          
          nightsSpan.textContent = noches;
          subtotalSpan.textContent = '€' + subtotal;
          totalSpan.textContent = '€' + total;
          summary.style.display = 'block';
        }
      }
    }
    
    checkin.addEventListener('change', function() {
      const tomorrow = new Date(checkin.value);
      tomorrow.setDate(tomorrow.getDate() + 1);
      checkout.min = tomorrow.toISOString().split('T')[0];
      calcularTotal();
    });
    
    checkout.addEventListener('change', calcularTotal);
  </script>
</body>
</html>