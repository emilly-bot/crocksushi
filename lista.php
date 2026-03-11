<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Presentes – Chá de Panela</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap');

* { box-sizing: border-box; margin: 0; padding: 0; }
body {
  font-family: 'Lato', sans-serif;
  background: #fdf6f0;
  color: #3a2a1a;
  min-height: 100vh;
}

/* ── Hero ───────────────────────────────────────────────────────────────── */
.hero {
  background: linear-gradient(135deg, #f8e4d9 0%, #fce8ef 50%, #f4d9e8 100%);
  padding: 50px 20px 40px;
  text-align: center;
  border-bottom: 2px solid #f0c4d0;
  position: relative;
  overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e8a4b8' fill-opacity='0.12'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  pointer-events: none;
}
.hero-icon { font-size: 3.5rem; display: block; margin-bottom: 12px; }
.hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.8rem, 5vw, 2.8rem);
  color: #7a2d4a;
  margin-bottom: 8px;
  text-shadow: 0 2px 8px rgba(122,45,74,.15);
}
.hero .subtitle {
  font-size: 1rem;
  color: #a0627a;
  margin-bottom: 6px;
  font-style: italic;
}
.hero .info-pill {
  display: inline-block;
  background: rgba(255,255,255,.7);
  border: 1px solid #e8b4c4;
  border-radius: 50px;
  padding: 6px 20px;
  font-size: 0.85rem;
  color: #7a2d4a;
  margin-top: 10px;
  backdrop-filter: blur(4px);
}

/* ── Notice ─────────────────────────────────────────────────────────────── */
.notice {
  max-width: 700px;
  margin: 20px auto 0;
  background: #fff8f5;
  border: 1px solid #f0c4d0;
  border-radius: 14px;
  padding: 16px 22px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: 0.88rem;
  color: #7a5060;
  line-height: 1.5;
}
.notice-icon { font-size: 1.3rem; flex-shrink: 0; margin-top: 1px; }

/* ── Counter ────────────────────────────────────────────────────────────── */
.counter-bar {
  background: #fff;
  border-bottom: 1px solid #f0d8e0;
  padding: 14px 20px;
  display: flex;
  justify-content: center;
  gap: 28px;
  flex-wrap: wrap;
}
.counter-item { text-align: center; }
.counter-item .num {
  display: block;
  font-family: 'Playfair Display', serif;
  font-size: 1.6rem;
  font-weight: 700;
  color: #7a2d4a;
}
.counter-item .lbl { font-size: 0.75rem; color: #a0627a; text-transform: uppercase; letter-spacing: .5px; }
.progress-wrap { width: 100%; max-width: 300px; background: #f0d8e0; border-radius: 10px; height: 8px; margin: 10px auto 0; overflow: hidden; }
.progress-bar  { height: 100%; background: linear-gradient(90deg, #e8709a, #c4447a); border-radius: 10px; transition: width .6s ease; }

/* ── Grid ───────────────────────────────────────────────────────────────── */
.section-title {
  text-align: center;
  padding: 30px 20px 16px;
  font-family: 'Playfair Display', serif;
  font-size: 1.4rem;
  color: #7a2d4a;
}
.gifts-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 20px;
  padding: 0 20px 50px;
  max-width: 1100px;
  margin: 0 auto;
}

/* ── Card ───────────────────────────────────────────────────────────────── */
.gift-card {
  background: #fff;
  border-radius: 18px;
  overflow: hidden;
  border: 2px solid #f4dce6;
  box-shadow: 0 4px 16px rgba(122,45,74,.08);
  transition: transform .2s, box-shadow .2s;
  cursor: pointer;
  position: relative;
}
.gift-card:hover:not(.reservado) {
  transform: translateY(-4px);
  box-shadow: 0 10px 28px rgba(122,45,74,.18);
  border-color: #e8709a;
}
.gift-card.reservado {
  cursor: default;
  filter: grayscale(1);
  opacity: .7;
  border-color: #ddd;
}
.gift-card.reservado .card-overlay {
  display: flex;
}

.card-img {
  width: 100%; height: 180px;
  background: linear-gradient(135deg, #fce8ef, #f4d9e8);
  display: flex; align-items: center; justify-content: center;
  font-size: 4rem;
  overflow: hidden;
  position: relative;
}
.card-img img {
  width: 100%; height: 100%;
  object-fit: cover;
}
.card-img .emoji-fallback { font-size: 4rem; }

.card-overlay {
  display: none;
  position: absolute; inset: 0;
  background: rgba(0,0,0,.45);
  align-items: center; justify-content: center;
  flex-direction: column; gap: 6px;
  border-radius: 16px;
}
.overlay-tag {
  background: #555;
  color: #fff;
  padding: 6px 18px;
  border-radius: 50px;
  font-size: 0.82rem;
  font-weight: 700;
  letter-spacing: .5px;
}
.overlay-name {
  color: #ddd;
  font-size: 0.78rem;
}

.card-body { padding: 16px; }
.card-nome {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem;
  font-weight: 600;
  color: #3a2a1a;
  margin-bottom: 6px;
  line-height: 1.3;
}
.card-desc {
  font-size: 0.82rem;
  color: #8a6a7a;
  margin-bottom: 10px;
  line-height: 1.4;
  min-height: 34px;
}
.card-footer {
  display: flex; align-items: center; justify-content: space-between; gap: 8px;
}
.card-valor {
  font-size: 1rem;
  font-weight: 700;
  color: #7a2d4a;
}
.card-btn {
  background: linear-gradient(135deg, #e8709a, #c4447a);
  color: #fff;
  border: none;
  padding: 8px 16px;
  border-radius: 10px;
  font-size: 0.82rem;
  font-weight: 700;
  cursor: pointer;
  transition: .2s;
  white-space: nowrap;
}
.card-btn:hover { background: linear-gradient(135deg, #d4607a, #b03468); }
.card-btn.taken { background: #bbb; cursor: default; }

/* ── Modal ──────────────────────────────────────────────────────────────── */
.modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.55);
  z-index: 500; justify-content: center; align-items: center;
  padding: 20px;
}
.modal-overlay.show { display: flex; }
.modal {
  background: #fff;
  border-radius: 22px;
  padding: 32px 28px;
  max-width: 460px; width: 100%;
  border: 2px solid #f0c4d0;
  animation: modalIn .25s ease;
  position: relative;
  max-height: 90vh; overflow-y: auto;
}
@keyframes modalIn { from{opacity:0;transform:scale(.94)} to{opacity:1;transform:scale(1)} }
.modal-close {
  position: absolute; top: 14px; right: 14px;
  background: #f0e0e8; border: none; color: #7a2d4a;
  width: 32px; height: 32px; border-radius: 50%;
  cursor: pointer; font-size: 1rem; line-height: 32px; text-align: center;
  transition: .2s;
}
.modal-close:hover { background: #e8709a; color: #fff; }
.modal-gift-preview {
  display: flex; gap: 14px; align-items: center;
  background: #fdf0f4; border-radius: 14px; padding: 14px;
  margin-bottom: 22px; border: 1px solid #f4d0dc;
}
.modal-gift-img {
  width: 70px; height: 70px; border-radius: 10px;
  background: linear-gradient(135deg, #fce8ef, #f4d9e8);
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; flex-shrink: 0; overflow: hidden;
}
.modal-gift-img img { width: 100%; height: 100%; object-fit: cover; border-radius: 10px; }
.modal-gift-nome {
  font-family: 'Playfair Display', serif;
  font-size: 1.05rem; font-weight: 700; color: #3a2a1a;
  margin-bottom: 4px;
}
.modal-gift-valor { font-size: 0.9rem; color: #7a2d4a; font-weight: 700; }
.modal-gift-desc  { font-size: 0.78rem; color: #a08090; margin-top: 2px; }

.modal h2 {
  font-family: 'Playfair Display', serif;
  font-size: 1.3rem; color: #7a2d4a;
  margin-bottom: 6px;
}
.modal .modal-sub { font-size: 0.85rem; color: #a08090; margin-bottom: 22px; }

.form-field { margin-bottom: 16px; }
.form-field label {
  display: block; font-size: 0.75rem; color: #a08090;
  text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px;
  font-weight: 700;
}
.form-field input {
  width: 100%; background: #fdf8fb; border: 2px solid #f0d0dc;
  color: #3a2a1a; padding: 11px 14px; border-radius: 12px;
  font-size: 0.95rem; font-family: inherit; transition: .2s;
}
.form-field input:focus { outline: none; border-color: #e8709a; background: #fff; }

.payment-options { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
.pay-option {
  border: 2px solid #f0d0dc;
  border-radius: 14px; padding: 14px 12px; cursor: pointer;
  text-align: center; transition: .2s; background: #fdf8fb;
  position: relative;
}
.pay-option:hover { border-color: #e8709a; background: #fff5f8; }
.pay-option.selected { border-color: #c4447a; background: #fff0f5; }
.pay-option input[type=radio] { display: none; }
.pay-option .pay-icon { font-size: 1.6rem; display: block; margin-bottom: 6px; }
.pay-option .pay-title { font-size: 0.85rem; font-weight: 700; color: #3a2a1a; }
.pay-option .pay-sub   { font-size: 0.72rem; color: #a08090; margin-top: 3px; line-height: 1.3; }
.pay-option.selected .pay-title { color: #7a2d4a; }

.pix-box {
  display: none;
  background: #fff0f5; border: 2px dashed #e8709a;
  border-radius: 14px; padding: 14px; margin-bottom: 18px;
  text-align: center;
}
.pix-box.show { display: block; }
.pix-box .pix-label { font-size: 0.75rem; color: #a08090; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
.pix-box .pix-key {
  font-size: 1.15rem; font-weight: 700; color: #7a2d4a;
  letter-spacing: 1px; user-select: all; cursor: copy;
}
.pix-box .pix-hint { font-size: 0.75rem; color: #a08090; margin-top: 4px; }
.pix-copy-btn {
  background: #e8709a; color: #fff; border: none;
  padding: 6px 16px; border-radius: 8px; font-size: 0.78rem;
  cursor: pointer; margin-top: 8px; font-weight: 700;
}
.pix-copy-btn:hover { background: #c4447a; }

.presencial-box {
  display: none;
  background: #f5fff0; border: 2px dashed #74c47a;
  border-radius: 14px; padding: 14px; margin-bottom: 18px;
  text-align: center; font-size: 0.85rem; color: #2a6a30;
  line-height: 1.5;
}
.presencial-box.show { display: block; }

.modal-confirm-btn {
  width: 100%;
  background: linear-gradient(135deg, #e8709a, #c4447a);
  color: #fff; border: none;
  padding: 14px; border-radius: 14px;
  font-size: 1rem; font-weight: 700; cursor: pointer;
  transition: .2s; font-family: inherit;
}
.modal-confirm-btn:hover { background: linear-gradient(135deg, #d4607a, #b03468); }
.modal-confirm-btn:disabled { opacity: .5; cursor: default; }

.modal-err { color: #c44460; font-size: 0.82rem; margin-top: 8px; display: none; }

/* ── Success Modal ──────────────────────────────────────────────────────── */
.success-modal {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.55);
  z-index: 600; justify-content: center; align-items: center;
  padding: 20px;
}
.success-modal.show { display: flex; }
.success-card {
  background: #fff; border-radius: 22px;
  padding: 36px 28px; max-width: 400px; width: 100%;
  text-align: center; border: 2px solid #b4dab8;
  animation: modalIn .25s ease;
}
.success-card .s-icon { font-size: 3.5rem; display: block; margin-bottom: 14px; }
.success-card h3 {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem; color: #2a6a30; margin-bottom: 8px;
}
.success-card p { font-size: 0.9rem; color: #608060; line-height: 1.5; margin-bottom: 20px; }
.success-card .close-btn {
  background: linear-gradient(135deg, #74c47a, #3a9a40);
  color: #fff; border: none; padding: 12px 32px;
  border-radius: 12px; font-size: 0.95rem; font-weight: 700;
  cursor: pointer; font-family: inherit;
}

/* ── Loading ────────────────────────────────────────────────────────────── */
.loading {
  text-align: center; padding: 60px 20px; color: #c08090;
}
.loading .spinner {
  width: 40px; height: 40px; border: 3px solid #f0d8e0;
  border-top-color: #e8709a; border-radius: 50%;
  animation: spin .7s linear infinite;
  margin: 0 auto 16px;
}
@keyframes spin { to { transform: rotate(360deg); } }

.empty-state {
  text-align: center; padding: 60px 20px; color: #c08090;
  grid-column: 1/-1;
}
.empty-state .e-icon { font-size: 3.5rem; display: block; margin-bottom: 12px; }

footer {
  text-align: center; padding: 20px;
  font-size: 0.78rem; color: #c0a0b0;
  border-top: 1px solid #f0d8e0;
}
</style>
</head>
<body>

<!-- ── HERO ─────────────────────────────────────────────────────────────────── -->
<section class="hero">
  <span class="hero-icon">💍🏠</span>
  <h1>Lista de Presentes</h1>
  <p class="subtitle">Chá de Panela</p>
  <span class="info-pill">Escolha um presente para nos ajudar a montar nosso lar</span>

  <div class="notice" style="margin: 20px auto 0; max-width: 600px;">
    <span class="notice-icon">💡</span>
    <div>
      Clique no presente que deseja dar, informe seu nome e escolha como prefere presentear.
      Cada item pode ser escolhido por <strong>apenas uma pessoa</strong> — os itens já escolhidos aparecem em cinza.
    </div>
  </div>
</section>

<!-- ── CONTADORES ─────────────────────────────────────────────────────────── -->
<div class="counter-bar">
  <div class="counter-item">
    <span class="num" id="cTotal">0</span>
    <span class="lbl">Total de itens</span>
  </div>
  <div class="counter-item">
    <span class="num" id="cDisponiveis">0</span>
    <span class="lbl">Disponíveis</span>
  </div>
  <div class="counter-item">
    <span class="num" id="cReservados">0</span>
    <span class="lbl">Já escolhidos</span>
  </div>
</div>
<div style="max-width:300px;margin:0 auto;padding:8px 20px 0;">
  <div class="progress-wrap"><div class="progress-bar" id="progressBar" style="width:0%"></div></div>
</div>

<!-- ── GRID ──────────────────────────────────────────────────────────────── -->
<p class="section-title">Gostaríamos muito de receber</p>
<div class="gifts-grid" id="giftsGrid">
  <div class="loading" style="grid-column:1/-1;">
    <div class="spinner"></div>
    Carregando lista...
  </div>
</div>

<!-- ── MODAL DE RESERVA ──────────────────────────────────────────────────── -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal">
    <button class="modal-close" onclick="closeModal()">✕</button>

    <div class="modal-gift-preview" id="modalPreview">
      <div class="modal-gift-img" id="modalImg"><span>🎁</span></div>
      <div>
        <div class="modal-gift-nome" id="modalNome">—</div>
        <div class="modal-gift-valor" id="modalValor"></div>
        <div class="modal-gift-desc" id="modalDesc"></div>
      </div>
    </div>

    <h2>Que presente lindo!</h2>
    <p class="modal-sub">Preencha seu nome para reservar este presente.</p>

    <div class="form-field">
      <label>Seu nome completo *</label>
      <input type="text" id="inputNome" placeholder="Ex: Maria e João" maxlength="120" />
    </div>

    <div class="form-field">
      <label>Como você prefere presentear?</label>
      <div class="payment-options">
        <label class="pay-option" id="optPix" onclick="selectPay('pix')">
          <input type="radio" name="forma" value="pix">
          <span class="pay-icon">📱</span>
          <div class="pay-title">PIX</div>
          <div class="pay-sub">Transferência via PIX</div>
        </label>
        <label class="pay-option" id="optPresencial" onclick="selectPay('presencial')">
          <input type="radio" name="forma" value="presencial">
          <span class="pay-icon">🎁</span>
          <div class="pay-title">Presencial</div>
          <div class="pay-sub">Levo no dia do evento</div>
        </label>
      </div>
    </div>

    <div class="pix-box" id="pixBox">
      <div class="pix-label">Chave PIX</div>
      <div class="pix-key" id="pixKey">068.692.021-09</div>
      <div class="pix-hint">CPF — clique para copiar</div>
      <button class="pix-copy-btn" onclick="copyPix()">📋 Copiar chave</button>
    </div>

    <div class="presencial-box" id="presencialBox">
      Ótimo! Você pode trazer o presente no dia do evento.<br>
      Te esperamos com muito carinho!
    </div>

    <p class="modal-err" id="modalErr"></p>
    <button class="modal-confirm-btn" id="confirmBtn" onclick="confirmarReserva()">
      Confirmar presente
    </button>
  </div>
</div>

<!-- ── MODAL DE SUCESSO ──────────────────────────────────────────────────── -->
<div class="success-modal" id="successModal">
  <div class="success-card">
    <span class="s-icon">🎉</span>
    <h3>Presente reservado!</h3>
    <p id="successMsg">Muito obrigado! Seu presente foi confirmado.</p>
    <button class="close-btn" onclick="closeSuccess()">Ver a lista</button>
  </div>
</div>

<footer>
  Feito com muito amor para o nosso chá de panela 💕
</footer>

<script>
const PIX_KEY = '068.692.021-09';
let allGifts = [];
let selectedGiftId = null;
let selectedPay = null;

// ── Carregar presentes ────────────────────────────────────────────────────
async function loadGifts() {
  try {
    const res  = await fetch('api/gifts.php?_=' + Date.now());
    const data = await res.json();
    allGifts   = data.gifts || [];
    renderGifts();
  } catch(e) {
    document.getElementById('giftsGrid').innerHTML =
      '<div class="empty-state"><span class="e-icon">😢</span>Não foi possível carregar a lista.</div>';
  }
}

// ── Render ────────────────────────────────────────────────────────────────
function renderGifts() {
  const grid = document.getElementById('giftsGrid');
  const total      = allGifts.length;
  const reservados = allGifts.filter(g => g.reservado == 1).length;
  const disponiveis = total - reservados;

  document.getElementById('cTotal').textContent       = total;
  document.getElementById('cDisponiveis').textContent  = disponiveis;
  document.getElementById('cReservados').textContent   = reservados;
  const pct = total > 0 ? Math.round((reservados/total)*100) : 0;
  document.getElementById('progressBar').style.width  = pct + '%';

  if (!total) {
    grid.innerHTML = '<div class="empty-state"><span class="e-icon">🛍️</span>A lista ainda está sendo montada.<br>Volte em breve!</div>';
    return;
  }

  let html = '';
  allGifts.forEach(function(g) {
    const reservado = g.reservado == 1;
    const valor     = g.valor > 0 ? 'R$ ' + parseFloat(g.valor).toFixed(2).replace('.',',') : '';

    let imgHtml = '';
    if (g.imagem_url) {
      imgHtml = '<img src="' + escHtml(g.imagem_url) + '" alt="' + escHtml(g.nome) + '" onerror="this.style.display=\'none\';this.nextSibling.style.display=\'block\'">' +
                '<span class="emoji-fallback" style="display:none">🎁</span>';
    } else {
      imgHtml = '<span class="emoji-fallback">🎁</span>';
    }

    html += '<div class="gift-card' + (reservado ? ' reservado' : '') + '"' +
            (!reservado ? ' onclick="openModal(' + g.id + ')"' : '') + '>';

    html += '<div class="card-img">' + imgHtml + '</div>';

    if (reservado) {
      html += '<div class="card-overlay">';
      html += '<span class="overlay-tag">Já escolhido</span>';
      html += '<span class="overlay-name">por ' + escHtml(g.reservado_por) + '</span>';
      html += '</div>';
    }

    html += '<div class="card-body">';
    html += '<div class="card-nome">' + escHtml(g.nome) + '</div>';
    if (g.descricao) html += '<div class="card-desc">' + escHtml(g.descricao) + '</div>';
    else html += '<div class="card-desc"></div>';
    html += '<div class="card-footer">';
    if (valor) html += '<span class="card-valor">' + valor + '</span>';
    else html += '<span></span>';
    if (!reservado) {
      html += '<button class="card-btn" onclick="event.stopPropagation();openModal(' + g.id + ')">Quero dar este</button>';
    } else {
      html += '<button class="card-btn taken" disabled>Já escolhido</button>';
    }
    html += '</div></div></div>';
  });

  grid.innerHTML = html;
}

function escHtml(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Modal de reserva ──────────────────────────────────────────────────────
function openModal(id) {
  const g = allGifts.find(function(x){ return x.id == id; });
  if (!g || g.reservado == 1) return;

  selectedGiftId = id;
  selectedPay    = null;

  // Preview
  const imgEl = document.getElementById('modalImg');
  if (g.imagem_url) {
    imgEl.innerHTML = '<img src="' + escHtml(g.imagem_url) + '" onerror="this.outerHTML=\'<span>🎁</span>\'">';
  } else {
    imgEl.innerHTML = '<span>🎁</span>';
  }

  document.getElementById('modalNome').textContent  = g.nome;
  document.getElementById('modalDesc').textContent  = g.descricao || '';
  document.getElementById('modalValor').textContent =
    g.valor > 0 ? 'Valor aprox.: R$ ' + parseFloat(g.valor).toFixed(2).replace('.',',') : '';

  // Reset form
  document.getElementById('inputNome').value = '';
  document.getElementById('modalErr').style.display = 'none';
  document.getElementById('confirmBtn').disabled = false;
  document.getElementById('confirmBtn').textContent = 'Confirmar presente';
  document.getElementById('optPix').classList.remove('selected');
  document.getElementById('optPresencial').classList.remove('selected');
  document.getElementById('pixBox').classList.remove('show');
  document.getElementById('presencialBox').classList.remove('show');

  document.getElementById('modalOverlay').classList.add('show');
  setTimeout(function(){ document.getElementById('inputNome').focus(); }, 200);
}

function closeModal() {
  document.getElementById('modalOverlay').classList.remove('show');
}

document.getElementById('modalOverlay').addEventListener('click', function(e){
  if (e.target === this) closeModal();
});

function selectPay(tipo) {
  selectedPay = tipo;
  document.getElementById('optPix').classList.toggle('selected', tipo === 'pix');
  document.getElementById('optPresencial').classList.toggle('selected', tipo === 'presencial');
  document.getElementById('pixBox').classList.toggle('show', tipo === 'pix');
  document.getElementById('presencialBox').classList.toggle('show', tipo === 'presencial');
}

function copyPix() {
  navigator.clipboard.writeText(PIX_KEY).then(function(){
    const btn = document.querySelector('.pix-copy-btn');
    const orig = btn.textContent;
    btn.textContent = '✅ Copiado!';
    setTimeout(function(){ btn.textContent = orig; }, 2000);
  }).catch(function(){
    prompt('Copie a chave PIX:', PIX_KEY);
  });
}

// ── Confirmar reserva ─────────────────────────────────────────────────────
async function confirmarReserva() {
  const nome = document.getElementById('inputNome').value.trim();
  const err  = document.getElementById('modalErr');
  const btn  = document.getElementById('confirmBtn');

  err.style.display = 'none';

  if (!nome) {
    err.textContent = '⚠️ Informe seu nome para continuar.';
    err.style.display = 'block';
    document.getElementById('inputNome').focus();
    return;
  }
  if (!selectedPay) {
    err.textContent = '⚠️ Escolha como prefere presentear.';
    err.style.display = 'block';
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Confirmando...';

  try {
    const res = await fetch('api/gifts.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        action: 'reservar',
        id: selectedGiftId,
        reservado_por: nome,
        forma_pagamento: selectedPay
      })
    });
    const data = await res.json();

    if (res.ok && data.success) {
      closeModal();
      const g = data.gift;
      let msg = 'Obrigado, <strong>' + escHtml(nome) + '</strong>! Você escolheu presentear com <strong>' + escHtml(g.nome) + '</strong>.';
      if (selectedPay === 'pix') {
        msg += '<br><br>Envie via PIX para a chave <strong>' + escHtml(PIX_KEY) + '</strong> (CPF).';
      } else {
        msg += '<br><br>Você pode trazer o presente no dia do evento. Te esperamos!';
      }
      document.getElementById('successMsg').innerHTML = msg;
      document.getElementById('successModal').classList.add('show');
      await loadGifts(); // Atualiza a lista
    } else {
      throw new Error(data.error || 'Não foi possível reservar. Tente novamente.');
    }
  } catch(e) {
    err.textContent = '❌ ' + e.message;
    err.style.display = 'block';
    btn.disabled = false;
    btn.textContent = 'Confirmar presente';
  }
}

function closeSuccess() {
  document.getElementById('successModal').classList.remove('show');
}

// Tecla Enter no input
document.getElementById('inputNome').addEventListener('keydown', function(e){
  if (e.key === 'Enter') confirmarReserva();
});

loadGifts();
</script>
</body>
</html>
