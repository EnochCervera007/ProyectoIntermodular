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

// ===== PREVISUALIZACIÓN DE MÚLTIPLES IMÁGENES =====
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('imagenes');
const previewGrid = document.getElementById('previewGrid');
let previewFiles = [];

if (uploadArea && fileInput && previewGrid) {
  fileInput.addEventListener('change', () => {
    const newFiles = Array.from(fileInput.files);
    previewFiles = previewFiles.concat(newFiles);
    renderPreviews();
  });

  uploadArea.addEventListener('dragover', e => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
  });

  uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
  });

  uploadArea.addEventListener('drop', e => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    if (e.dataTransfer.files.length) {
      const newFiles = Array.from(e.dataTransfer.files);
      previewFiles = previewFiles.concat(newFiles);
      renderPreviews();
    }
  });

  function renderPreviews() {
    previewGrid.innerHTML = '';
    previewFiles.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = e => {
        const item = document.createElement('div');
        item.className = 'preview-item';
        item.innerHTML = `
          <img src="${e.target.result}" alt="Foto ${index + 1}">
          <button type="button" class="remove-img" data-index="${index}">✕</button>
        `;
        previewGrid.appendChild(item);

        item.querySelector('.remove-img').addEventListener('click', () => {
          previewFiles.splice(index, 1);
          rebuildFileList();
          renderPreviews();
        });
      };
      reader.readAsDataURL(file);
    });
  }

  function rebuildFileList() {
    const dt = new DataTransfer();
    previewFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
  }
}

// Also handle the inline form on index.php
const uploadAreaInline = document.querySelector('.guardar-form .upload-area');
const fileInputInline = document.querySelector('.guardar-form input[type="file"][name="imagenes[]"]');
const previewGridInline = document.querySelector('.guardar-form .preview-grid');
let previewFilesInline = [];

if (uploadAreaInline && fileInputInline && previewGridInline) {
  const ua = uploadAreaInline, fi = fileInputInline, pg = previewGridInline;

  fi.addEventListener('change', () => {
    const newFiles = Array.from(fi.files);
    previewFilesInline = previewFilesInline.concat(newFiles);
    renderInlinePreviews();
  });

  ua.addEventListener('dragover', e => { e.preventDefault(); ua.classList.add('dragover'); });
  ua.addEventListener('dragleave', () => { ua.classList.remove('dragover'); });
  ua.addEventListener('drop', e => {
    e.preventDefault();
    ua.classList.remove('dragover');
    if (e.dataTransfer.files.length) {
      previewFilesInline = previewFilesInline.concat(Array.from(e.dataTransfer.files));
      renderInlinePreviews();
    }
  });

  function renderInlinePreviews() {
    pg.innerHTML = '';
    previewFilesInline.forEach((file, index) => {
      const reader = new FileReader();
      reader.onload = e => {
        const item = document.createElement('div');
        item.className = 'preview-item';
        item.innerHTML = `
          <img src="${e.target.result}" alt="Foto ${index + 1}">
          <button type="button" class="remove-img" data-index="${index}">✕</button>
        `;
        pg.appendChild(item);
        item.querySelector('.remove-img').addEventListener('click', () => {
          previewFilesInline.splice(index, 1);
          rebuildInlineFileList();
          renderInlinePreviews();
        });
      };
      reader.readAsDataURL(file);
    });
  }

  function rebuildInlineFileList() {
    const dt = new DataTransfer();
    previewFilesInline.forEach(f => dt.items.add(f));
    fi.files = dt.files;
  }
}