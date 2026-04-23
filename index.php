<?php
session_start();
$logged_in = isset($_SESSION['user_id']);
$user_name = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ApBarcelona – Apartamentos Turísticos</title>
  <link rel="stylesheet" href="style.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="nav-inner">
      <a href="#" class="logo">Ap<span>Barcelona</span></a>
      <ul class="nav-links">
        <li><a href="#apartamentos">Apartamentos</a></li>
        <li><a href="#nosotros">Nosotros</a></li>
        <li><a href="#contacto">Contacto</a></li>
      </ul>
      <?php if ($logged_in): ?>
        <div class="nav-buttons">
          <a href="guardar_apartamento.php" class="btn-nav">Publicar</a>
          <a href="mis_reservas.php" class="btn-nav">Mis Reservas</a>
          <a href="logout.php" class="btn-nav"><?php echo htmlspecialchars($user_name); ?></a>
        </div>
      <?php else: ?>
        <div class="nav-buttons">
          <a href="login.php" class="btn-nav">Iniciar Sesión</a>
          <a href="registro.php" class="btn-nav btn-reg">Registrarse</a>
        </div>
      <?php endif; ?>
    </div>
  </nav>

  <!-- HERO -->
  <header class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
      <p class="hero-tag">Barcelona, Catalunya</p>
      <h1>Tu hogar<br/><span>en Barcelona</span></h1>
      <p class="hero-sub">Apartamentos turísticos de alta calidad en los mejores barrios de la ciudad condal. Vive Barcelona como un local.</p>
      <div class="hero-btns">
        <a href="#apartamentos" class="btn-primary">Ver apartamentos</a>
        <a href="login.php" class="btn-secondary">Publica el tuyo</a>
      </div>
    </div>
    <div class="hero-stats">
      <div class="stat"><span>+240</span><p>Apartamentos</p></div>
      <div class="stat"><span>+4.8★</span><p>Valoración media</p></div>
      <div class="stat"><span>+12</span><p>Barrios</p></div>
    </div>
  </header>

  <!-- BARRIOS DESTACADOS -->
  <section class="barrios">
    <div class="container">
      <h2 class="section-title">Barrios <span>populares</span></h2>
      <div class="barrios-grid">
        <div class="barrio-card">
          <div class="barrio-img" style="background-image:url('https://images.unsplash.com/photo-1583422409516-2895a77efded?w=600')"></div>
          <div class="barrio-info"><h3>Gràcia</h3><p>18 apartamentos</p></div>
        </div>
        <div class="barrio-card large">
          <div class="barrio-img" style="background-image:url('https://images.unsplash.com/photo-1539037116277-4db20889f2d4?w=800')"></div>
          <div class="barrio-info"><h3>Barceloneta</h3><p>34 apartamentos</p></div>
        </div>
        <div class="barrio-card">
          <div class="barrio-img" style="background-image:url('https://images.unsplash.com/photo-1511527661048-7fe73d85e9a4?w=600')"></div>
          <div class="barrio-info"><h3>Eixample</h3><p>52 apartamentos</p></div>
        </div>
        <div class="barrio-card">
          <div class="barrio-img" style="background-image:url('https://images.unsplash.com/photo-1562883676-8c7feb83f09b?w=600')"></div>
          <div class="barrio-info"><h3>El Born</h3><p>27 apartamentos</p></div>
        </div>
      </div>
    </div>
  </section>

  <!-- APARTAMENTOS DESTACADOS -->
  <section class="apartamentos" id="apartamentos">
    <div class="container">
      <div class="section-header">
        <h2 class="section-title">Apartamentos <span>destacados</span></h2>
        <div class="filtros">
          <button class="filtro active" data-filter="todos">Todos</button>
          <button class="filtro" data-filter="playa">Playa</button>
          <button class="filtro" data-filter="centro">Centro</button>
          <button class="filtro" data-filter="lujo">Lujo</button>
        </div>
      </div>

      <div class="apts-grid">

        <article class="apt-card" data-cat="playa">
          <div class="apt-img" style="background-image:url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=600')">
            <span class="apt-badge">Playa</span>
            <button class="btn-fav">♡</button>
          </div>
          <div class="apt-body">
            <div class="apt-top">
              <h3>Piso Luminoso Barceloneta</h3>
              <p class="apt-price">€95<span>/noche</span></p>
            </div>
            <p class="apt-loc">📍 Barceloneta</p>
            <div class="apt-feats">
              <span>🛏 2 hab.</span>
              <span>🚿 1 baño</span>
              <span>👥 4 personas</span>
            </div>
            <div class="apt-footer">
              <p class="apt-rating">★ 4.9 <span>(84 reseñas)</span></p>
              <a href="apartamento.php?id=1" class="btn-ver">Ver más</a>
            </div>
          </div>
        </article>

        <article class="apt-card" data-cat="centro">
          <div class="apt-img" style="background-image:url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=600')">
            <span class="apt-badge">Centro</span>
            <button class="btn-fav">♡</button>
          </div>
          <div class="apt-body">
            <div class="apt-top">
              <h3>Loft Moderno Eixample</h3>
              <p class="apt-price">€120<span>/noche</span></p>
            </div>
            <p class="apt-loc">📍 Eixample Dret</p>
            <div class="apt-feats">
              <span>🛏 1 hab.</span>
              <span>🚿 1 baño</span>
              <span>👥 2 personas</span>
            </div>
            <div class="apt-footer">
              <p class="apt-rating">★ 4.7 <span>(61 reseñas)</span></p>
              <a href="apartamento.php?id=1" class="btn-ver">Ver más</a>
            </div>
          </div>
        </article>

        <article class="apt-card featured" data-cat="lujo">
          <div class="apt-img" style="background-image:url('https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=800')">
            <span class="apt-badge lujo">Lujo</span>
            <button class="btn-fav">♡</button>
          </div>
          <div class="apt-body">
            <div class="apt-top">
              <h3>Penthouse con Vistas al Mar</h3>
              <p class="apt-price">€280<span>/noche</span></p>
            </div>
            <p class="apt-loc">📍 Port Olímpic</p>
            <div class="apt-feats">
              <span>🛏 3 hab.</span>
              <span>🚿 2 baños</span>
              <span>👥 6 personas</span>
            </div>
            <div class="apt-footer">
              <p class="apt-rating">★ 5.0 <span>(112 reseñas)</span></p>
              <a href="apartamento.php?id=1" class="btn-ver">Ver más</a>
            </div>
          </div>
        </article>

        <article class="apt-card" data-cat="centro">
          <div class="apt-img" style="background-image:url('https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=600')">
            <span class="apt-badge">Centro</span>
            <button class="btn-fav">♡</button>
          </div>
          <div class="apt-body">
            <div class="apt-top">
              <h3>Apartamento El Born</h3>
              <p class="apt-price">€88<span>/noche</span></p>
            </div>
            <p class="apt-loc">📍 El Born</p>
            <div class="apt-feats">
              <span>🛏 2 hab.</span>
              <span>🚿 1 baño</span>
              <span>👥 3 personas</span>
            </div>
            <div class="apt-footer">
              <p class="apt-rating">★ 4.8 <span>(48 reseñas)</span></p>
              <a href="apartamento.php?id=1" class="btn-ver">Ver más</a>
            </div>
          </div>
        </article>

        <article class="apt-card" data-cat="playa">
          <div class="apt-img" style="background-image:url('https://images.unsplash.com/photo-1484154218962-a197022b5858?w=600')">
            <span class="apt-badge">Playa</span>
            <button class="btn-fav">♡</button>
          </div>
          <div class="apt-body">
            <div class="apt-top">
              <h3>Estudio Vista Mar Poblenou</h3>
              <p class="apt-price">€74<span>/noche</span></p>
            </div>
            <p class="apt-loc">📍 Poblenou</p>
            <div class="apt-feats">
              <span>🛏 1 hab.</span>
              <span>🚿 1 baño</span>
              <span>👥 2 personas</span>
            </div>
            <div class="apt-footer">
              <p class="apt-rating">★ 4.6 <span>(37 reseñas)</span></p>
              <a href="apartamento.php?id=1" class="btn-ver">Ver más</a>
            </div>
          </div>
        </article>

        <article class="apt-card" data-cat="centro">
          <div class="apt-img" style="background-image:url('https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=600')">
            <span class="apt-badge">Centro</span>
            <button class="btn-fav">♡</button>
          </div>
          <div class="apt-body">
            <div class="apt-top">
              <h3>Piso Clásico Gràcia</h3>
              <p class="apt-price">€105<span>/noche</span></p>
            </div>
            <p class="apt-loc">📍 Gràcia</p>
            <div class="apt-feats">
              <span>🛏 2 hab.</span>
              <span>🚿 2 baños</span>
              <span>👥 4 personas</span>
            </div>
            <div class="apt-footer">
              <p class="apt-rating">★ 4.9 <span>(73 reseñas)</span></p>
              <a href="apartamento.php?id=1" class="btn-ver">Ver más</a>
            </div>
          </div>
        </article>

      </div>
    </div>
  </section>

  <!-- POR QUÉ ELEGIRNOS -->
  <section class="porque" id="nosotros">
    <div class="container">
      <h2 class="section-title">¿Por qué <span>elegirnos?</span></h2>
      <div class="porque-grid">
        <div class="porque-item">
          <div class="porque-icon">🏆</div>
          <h3>Calidad garantizada</h3>
          <p>Todos nuestros apartamentos pasan una inspección rigurosa antes de ser publicados.</p>
        </div>
        <div class="porque-item">
          <div class="porque-icon">🔒</div>
          <h3>Reserva segura</h3>
          <p>Pago 100% seguro y cancelación gratuita hasta 48h antes de tu llegada.</p>
        </div>
        <div class="porque-item">
          <div class="porque-icon">💬</div>
          <h3>Atención 24/7</h3>
          <p>Nuestro equipo está siempre disponible para ayudarte durante tu estancia.</p>
        </div>
        <div class="porque-item">
          <div class="porque-icon">🗝️</div>
          <h3>Check-in flexible</h3>
          <p>Entrada autónoma con caja de llaves inteligente. Llega cuando quieras.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FORMULARIO GUARDAR APARTAMENTO (conectado a PHP) -->
  <section class="guardar-form" id="guardar">
    <div class="container">
      <div class="form-wrapper">
        <div class="form-left">
          <h2>¿Tienes un <span>apartamento</span> en Barcelona?</h2>
          <p>Publica tu propiedad y empieza a recibir reservas. Rellena el formulario y nos pondremos en contacto contigo.</p>
          <ul class="form-benefits">
            <li>✅ Sin comisiones el primer mes</li>
            <li>✅ Fotografía profesional incluida</li>
            <li>✅ Gestión integral disponible</li>
            <li>✅ Visibilidad en toda la plataforma</li>
          </ul>
        </div>
        <div class="form-right">
          <form action="guardar_apartamento.php" method="POST" class="apt-form">
            <div class="form-row">
              <div class="form-group">
                <label for="nombre">Nombre completo *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Joan García" required/>
              </div>
              <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" placeholder="joan@ejemplo.com" required/>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="+34 600 000 000"/>
              </div>
              <div class="form-group">
                <label for="barrio">Barrio *</label>
                <select id="barrio" name="barrio" required>
                  <option value="">Selecciona un barrio</option>
                  <option>Barceloneta</option>
                  <option>Eixample</option>
                  <option>Gràcia</option>
                  <option>El Born</option>
                  <option>Poblenou</option>
                  <option>Sarrià</option>
                  <option>Les Corts</option>
                  <option>Otro</option>
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="habitaciones">Habitaciones *</label>
                <select id="habitaciones" name="habitaciones" required>
                  <option value="">Nº habitaciones</option>
                  <option>Estudio</option>
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                  <option>4+</option>
                </select>
              </div>
              <div class="form-group">
                <label for="precio">Precio por noche (€) *</label>
                <input type="number" id="precio" name="precio" placeholder="80" min="1" required/>
              </div>
            </div>
            <div class="form-group full">
              <label for="descripcion">Descripción del apartamento *</label>
              <textarea id="descripcion" name="descripcion" rows="4" placeholder="Describe tu apartamento: ubicación, características destacadas, servicios incluidos..." required></textarea>
            </div>
            <div class="form-group full">
              <label for="direccion">Dirección (no se mostrará públicamente) *</label>
              <input type="text" id="direccion" name="direccion" placeholder="Carrer de..., Barcelona" required/>
            </div>
            <button type="submit" class="btn-submit">Enviar mi apartamento →</button>
          </form>
        </div>
      </div>
    </div>
</section>

  <!-- FOOTER -->
  <footer class="footer" id="contacto">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-col">
          <h4>Explorar</h4>
          <ul>
            <li><a href="#">Todos los apartamentos</a></li>
            <li><a href="#">Barceloneta</a></li>
            <li><a href="#">Eixample</a></li>
            <li><a href="#">Gràcia</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Propietarios</h4>
          <ul>
            <li><a href="login.php">Publicar apartamento</a></li>
            <li><a href="como_funciona.php">Cómo funciona</a></li>
            <li><a href="tarifas.php">Tarifas</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>Contacto</h4>
          <ul>
            <li>apbarcelona@gmail.es</li>
            <li>📞 +34 932 000 000</li>
          </ul>
        </div>
      </div>
      <div class="footer-bottom">
        <p>© 2025 ApBarcelona. Todos los derechos reservados.</p>
        <div>
          <a href="privacidad.php">Privacidad</a>
          <a href="cookies.php">Cookies</a>
          <a href="terminos.php">Términos</a>
        </div>
      </div>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>