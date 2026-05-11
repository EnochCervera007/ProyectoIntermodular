// ===== FILTROS DE APARTAMENTOS =====
const filtros = document.querySelectorAll('.filtro');
const cards = document.querySelectorAll('.apt-card');

filtros.forEach(btn => {
  btn.addEventListener('click', () => {
    filtros.forEach(f => f.classList.remove('active'));
    btn.classList.add('active');

    const filter = btn.dataset.filter;
    cards.forEach(card => {
      if (filter === 'todos' || card.dataset.cat === filter) {
        card.style.display = 'block';
        card.style.animation = 'fadeUp 0.4s ease both';
      } else {
        card.style.display = 'none';
      }
    });
  });
});

// ===== BOTÓN FAVORITO =====
document.querySelectorAll('.btn-fav').forEach(btn => {
  btn.addEventListener('click', () => {
    btn.textContent = btn.textContent === '♡' ? '♥' : '♡';
    btn.style.background = btn.textContent === '♥' ? 'var(--rojo)' : '';
    btn.style.color = btn.textContent === '♥' ? 'white' : '';
  });
});

// ===== NAVBAR SCROLL SHADOW =====
const navbar = document.querySelector('.navbar');
window.addEventListener('scroll', () => {
  navbar.style.boxShadow = window.scrollY > 10 ? '0 4px 24px rgba(0,0,0,0.08)' : '';
});

// ===== MENSAJE DE ÉXITO DEL FORMULARIO (si PHP redirige con ?ok=1) =====
const params = new URLSearchParams(window.location.search);
if (params.get('ok') === '1') {
  const banner = document.createElement('div');
  banner.innerHTML = '✅ ¡Tu apartamento ha sido enviado correctamente! Nos pondremos en contacto contigo pronto.';
  banner.style.cssText = `
    position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%);
    background: #1a1a1a; color: white; padding: 16px 28px;
    border-radius: 50px; font-size: 0.95rem; font-weight: 600;
    box-shadow: 0 8px 30px rgba(0,0,0,0.25); z-index: 9999;
    animation: fadeUp 0.4s ease;
    border-left: 4px solid #C8102E;
  `;
  document.body.appendChild(banner);
  setTimeout(() => banner.remove(), 5000);
}

// ===== FOTOS: AÑADIR Y PREVISUALIZAR =====
function setupPhotoUpload(inputId, listId, countId) {
  const input = document.getElementById(inputId);
  const list = document.getElementById(listId);
  const count = document.getElementById(countId);
  if (!input || !list || !count) return;
  let files = [];

  input.addEventListener('change', () => {
    files = files.concat(Array.from(input.files));
    render();
  });

  function render() {
    list.innerHTML = '';
    count.textContent = files.length ? `${files.length} foto${files.length > 1 ? 's' : ''} seleccionada${files.length > 1 ? 's' : ''}` : 'Ninguna foto seleccionada';
    files.forEach((file, i) => {
      const r = new FileReader();
      r.onload = e => {
        const div = document.createElement('div');
        div.className = 'photo-thumb';
        div.innerHTML = `<img src="${e.target.result}"><button type="button" class="del" data-i="${i}">✕</button>`;
        list.appendChild(div);
        div.querySelector('.del').addEventListener('click', () => {
          files.splice(i, 1);
          rebuildInput();
          render();
        });
      };
      r.readAsDataURL(file);
    });
  }

  function rebuildInput() {
    const dt = new DataTransfer();
    files.forEach(f => dt.items.add(f));
    input.files = dt.files;
  }
}

setupPhotoUpload('imagenes', 'photoList', 'photoCount');