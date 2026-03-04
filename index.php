<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CrockSushi – Painel de Pedidos</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; background: #0d0d0d; color: #f0f0f0; min-height: 100vh; }

header {
  background: linear-gradient(135deg, #1a1a2e, #16213e);
  padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;
  border-bottom: 2px solid #e94560; position: sticky; top: 0; z-index: 100;
}
.logo { display: flex; align-items: center; gap: 10px; }
.logo h1 { font-size: 1.5rem; color: #fff; letter-spacing: 1px; }
.header-right { display: flex; align-items: center; gap: 12px; }
.badge { background: #e94560; color: white; border-radius: 50px; padding: 5px 16px; font-size: 0.85rem; font-weight: bold; display: none; animation: blink 0.6s infinite alternate; }
@keyframes blink { from{opacity:1} to{opacity:.4} }
.nav-btn { background: #1e1e3e; color: #fff; border: 1px solid #2a2a5a; padding: 7px 16px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; cursor: pointer; }
.nav-btn:hover { background: #2a2a5a; }

.status-bar { padding: 8px 20px; display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #666; }
.dot { width: 9px; height: 9px; border-radius: 50%; background: #555; flex-shrink: 0; }
.dot.on  { background: #4caf50; box-shadow: 0 0 6px #4caf50; }
.dot.err { background: #e94560; }

.summary { padding: 10px 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px; }
.stat { background: #1a1a2e; border-radius: 10px; padding: 12px 16px; border-left: 3px solid #333360; }
.stat.green  { border-left-color: #4caf50; }
.stat.red    { border-left-color: #e94560; }
.stat.yellow { border-left-color: #f9c74f; }
.stat label  { display: block; font-size: 0.72rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
.stat strong { font-size: 1.2rem; color: #fff; }

.filters { padding: 8px 20px; display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
.filters input { background: #111122; border: 1px solid #2a2a5a; color: #fff; padding: 6px 12px; border-radius: 20px; font-size: 0.83rem; }
.filters input:focus { outline: none; border-color: #e94560; }
.filter-btn { padding: 5px 14px; border-radius: 20px; border: 1px solid #2a2a5a; background: #111122; color: #aaa; cursor: pointer; font-size: 0.8rem; transition: .2s; }
.filter-btn.active { background: #e94560; border-color: #e94560; color: #fff; }
.count-badge { background: #1e1e3e; color: #aaa; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; margin-left: auto; }
.refresh-info { font-size: 0.78rem; color: #555; }

.orders-grid { padding: 10px 20px 30px; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 14px; }

.order-card {
  background: #1a1a2e; border-radius: 14px; padding: 16px;
  border-left: 4px solid #333360; position: relative;
  transition: transform .15s, opacity .15s; cursor: pointer;
}
.order-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.4); }
.order-card.is-new   { border-left-color: #4caf50; animation: slideIn .35s ease; }
.order-card.enviado  { border-left-color: #4caf50; background: #0d2a0d; }
.order-card.cancelado{ border-left-color: #e94560; background: #2a0d0d; }
@keyframes slideIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }

.status-tag { display: inline-block; border-radius: 6px; padding: 2px 10px; font-size: 0.7rem; font-weight: bold; margin-bottom: 8px; }
.status-tag.enviado   { background: #4caf50; color: #fff; }
.status-tag.cancelado { background: #e94560; color: #fff; }

.c-nome    { font-size: 1.25rem; font-weight: bold; color: #f9c74f; margin-bottom: 8px; text-shadow: 0 0 14px rgba(249,199,79,.4); letter-spacing: .4px; border-bottom: 1px solid #2a2a4a; padding-bottom: 8px; }
.c-produto { color: #fff; font-size: 0.95rem; margin-bottom: 6px; }
.c-valor   { color: #4caf50; font-size: 1.1rem; font-weight: bold; margin-bottom: 10px; }
.c-meta    { display: flex; gap: 12px; flex-wrap: wrap; }
.c-meta span { font-size: 0.78rem; color: #888; }
.c-obs     { margin-top: 10px; background: #0d0d1a; padding: 7px 11px; border-radius: 8px; font-size: 0.82rem; color: #ccc; }
.order-num { position: absolute; top: 12px; right: 12px; background: #1e1e3e; color: #888; border-radius: 50px; padding: 2px 10px; font-size: 0.72rem; }

.c-actions { display: flex; gap: 8px; margin-top: 12px; }
.act-btn { flex: 1; padding: 7px 6px; border-radius: 8px; border: none; cursor: pointer; font-size: 0.8rem; font-weight: bold; transition: .2s; }
.act-btn.enviar   { background: #1a3a1a; color: #4caf50; border: 1px solid #4caf50; }
.act-btn.enviar:hover   { background: #4caf50; color: #fff; }
.act-btn.cancelar { background: #3a1a1a; color: #e94560; border: 1px solid #e94560; }
.act-btn.cancelar:hover { background: #e94560; color: #fff; }
.act-btn.pendente { background: #2a2a1a; color: #f9c74f; border: 1px solid #f9c74f; }
.act-btn.pendente:hover { background: #f9c74f; color: #000; }
.act-btn.excluir  { background: #1e1e3e; color: #888; border: 1px solid #333; max-width: 38px; }
.act-btn.excluir:hover  { background: #e94560; color: #fff; border-color: #e94560; }
.act-btn:disabled { opacity: .4; cursor: default; }

.empty { grid-column: 1/-1; text-align: center; padding: 60px 20px; color: #444; }
.empty span { font-size: 3rem; display: block; margin-bottom: 12px; }

/* Modal */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.8); z-index: 500; justify-content: center; align-items: center; }
.modal-overlay.show { display: flex; }
.modal { background: #1a1a2e; border-radius: 18px; padding: 28px; max-width: 480px; width: 92%; border: 1px solid #2a2a5a; animation: modalIn .25s ease; max-height: 90vh; overflow-y: auto; position: relative; }
@keyframes modalIn { from{opacity:0;transform:scale(.95)} to{opacity:1;transform:scale(1)} }
.modal-close { position: absolute; top: 16px; right: 16px; background: #2a2a5a; border: none; color: #fff; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; font-size: 1rem; line-height: 32px; text-align: center; }
.modal-close:hover { background: #e94560; }
.modal h2 { color: #f9c74f; font-size: 1.2rem; margin-bottom: 20px; padding-right: 40px; }
.m-row { display: flex; gap: 12px; margin-bottom: 14px; align-items: flex-start; }
.m-icon { font-size: 1.2rem; flex-shrink: 0; }
.m-label { font-size: 0.7rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
.m-value { font-size: 0.95rem; color: #fff; }
.m-value.gold  { color: #f9c74f; font-size: 1.15rem; font-weight: bold; }
.m-value.green { color: #4caf50; font-size: 1.1rem; font-weight: bold; }
.m-divider { border: none; border-top: 1px solid #2a2a5a; margin: 14px 0; }
.modal-actions { display: flex; gap: 8px; margin-top: 18px; }
.modal-actions .act-btn { flex: 1; }

/* Toast */
.toast { position: fixed; bottom: 24px; right: 20px; background: #1a1a2e; border: 1px solid #4caf50; border-radius: 14px; padding: 14px 18px; z-index: 999; display: none; max-width: 310px; box-shadow: 0 4px 24px rgba(76,175,80,.35); }
.toast.show { display: block; }
.toast-title { color: #4caf50; font-weight: bold; margin-bottom: 4px; font-size: 0.9rem; }
.toast-body  { font-size: 0.85rem; color: #ccc; }
</style>
</head>
<body>

<header>
  <div class="logo">
    <span style="font-size:1.8rem">🥚🍣</span>
    <h1>CrockSushi</h1>
  </div>
  <div class="header-right">
    <span style="font-size:0.78rem;color:#666" id="nextRefresh"></span>
    <div class="badge" id="badge">🔔 NOVO PEDIDO!</div>
    <a class="nav-btn" href="pedido.php" target="_blank">➕ Novo Pedido</a>
  </div>
</header>

<div class="status-bar">
  <div class="dot" id="dot"></div>
  <span id="statusTxt">Carregando...</span>
</div>

<div class="summary">
  <div class="stat yellow"><label>📦 Total</label><strong id="sTotalPedidos">0</strong></div>
  <div class="stat"><label>⏳ Pendentes</label><strong id="sPendentes">0</strong></div>
  <div class="stat green"><label>✅ Enviados</label><strong id="sEnviados">0</strong></div>
  <div class="stat red"><label>❌ Cancelados</label><strong id="sCancelados">0</strong></div>
  <div class="stat green"><label>💰 Faturamento</label><strong id="sFaturamento">R$ 0,00</strong></div>
  <div class="stat"><label>🎯 Ticket Médio</label><strong id="sTicket">R$ 0,00</strong></div>
</div>

<div class="filters">
  <input type="text" id="filterText" placeholder="🔍 Buscar..." oninput="renderFiltered()" />
  <button class="filter-btn active" id="fb-todos"     onclick="setFilter('todos',this)">Todos</button>
  <button class="filter-btn"        id="fb-pendente"  onclick="setFilter('pendente',this)">⏳ Pendentes</button>
  <button class="filter-btn"        id="fb-enviado"   onclick="setFilter('enviado',this)">✅ Enviados</button>
  <button class="filter-btn"        id="fb-cancelado" onclick="setFilter('cancelado',this)">❌ Cancelados</button>
  <span class="count-badge" id="countBadge"></span>
</div>

<div class="orders-grid" id="grid">
  <div class="empty"><span>🥚</span>Carregando pedidos...</div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal" id="modal">
    <button class="modal-close" onclick="document.getElementById('modalOverlay').classList.remove('show')">✕</button>
    <h2>📋 Detalhes do Pedido</h2>
    <div id="modalContent"></div>
  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast">
  <div class="toast-title">🔔 Novo Pedido!</div>
  <div class="toast-body" id="toastBody"></div>
</div>

<script>
const INTERVAL = 30;
let allOrders   = [];
let activeFilter = 'pendente';
let lastIds     = new Set();
let timer       = null;
let countdown   = INTERVAL;

document.getElementById('modalOverlay').addEventListener('click', function(e) {
  if (e.target === this) this.classList.remove('show');
});

// ── Audio ──────────────────────────────────────────────────────────────────
function beep() {
  try {
    const ac = new (window.AudioContext || window.webkitAudioContext)();
    [[0,880,.6],[.25,1046,.6],[.5,1318,.8]].forEach(([t,f,vol]) => {
      const o=ac.createOscillator(), g=ac.createGain();
      o.connect(g); g.connect(ac.destination);
      o.type='triangle'; o.frequency.value=f;
      g.gain.setValueAtTime(vol,ac.currentTime+t);
      g.gain.exponentialRampToValueAtTime(.001,ac.currentTime+t+.22);
      o.start(ac.currentTime+t); o.stop(ac.currentTime+t+.25);
    });
  } catch(e){}
}

// ── Toast ──────────────────────────────────────────────────────────────────
function showToast(msg) {
  document.getElementById('toastBody').textContent = msg;
  const t = document.getElementById('toast');
  t.classList.add('show');
  clearTimeout(t._to);
  t._to = setTimeout(() => t.classList.remove('show'), 6000);
}

function flashBadge() {
  const b = document.getElementById('badge');
  b.style.display = 'block';
  clearTimeout(b._to);
  b._to = setTimeout(() => b.style.display='none', 7000);
}

// ── Format ─────────────────────────────────────────────────────────────────
function fmtVal(v) {
  const n = parseFloat(v);
  if (!v || isNaN(n) || n===0) return '';
  return 'R$ ' + n.toFixed(2).replace('.',',');
}

function fmtDate(d) {
  if (!d) return '';
  const p = d.split('-');
  if (p.length===3) return p[2]+'/'+p[1]+'/'+p[0];
  return d;
}

// ── Stats ──────────────────────────────────────────────────────────────────
function buildSummary(stats) {
  const fmtR = n => 'R$ ' + Number(n).toFixed(2).replace('.',',');
  document.getElementById('sTotalPedidos').textContent = stats.total;
  document.getElementById('sPendentes').textContent    = stats.pendentes;
  document.getElementById('sEnviados').textContent     = stats.enviados;
  document.getElementById('sCancelados').textContent   = stats.cancelados;
  document.getElementById('sFaturamento').textContent  = fmtR(stats.fat);
  document.getElementById('sTicket').textContent       = fmtR(stats.ticket);
}

// ── Filter ─────────────────────────────────────────────────────────────────
function setFilter(f, btn) {
  activeFilter = f;
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  renderFiltered();
}

// ── Render ─────────────────────────────────────────────────────────────────
function renderFiltered() {
  const q = document.getElementById('filterText').value.toLowerCase();
  let filtered = allOrders.slice();

  if (activeFilter !== 'todos') {
    filtered = filtered.filter(o => o.status === activeFilter);
  }

  if (q) filtered = filtered.filter(o =>
    [o.nome, o.produto, o.obs, o.horario].some(v => (v||'').toLowerCase().includes(q))
  );

  document.getElementById('countBadge').textContent = filtered.length + ' pedido' + (filtered.length!==1?'s':'');
  const grid = document.getElementById('grid');

  if (!filtered.length) {
    grid.innerHTML = '<div class="empty"><span>🔍</span>Nenhum pedido encontrado.</div>';
    return;
  }

  let html = '';
  filtered.forEach((o, i) => {
    const status  = o.status || 'pendente';
    const valor   = fmtVal(o.valor);
    const data    = fmtDate(o.data_entrega);

    let statusTag = '';
    if (status==='enviado')   statusTag = '<div class="status-tag enviado">✅ Enviado</div>';
    if (status==='cancelado') statusTag = '<div class="status-tag cancelado">❌ Cancelado</div>';

    const btnE = `<button class="act-btn enviar"   ${status==='enviado'?'disabled':''} onclick="event.stopPropagation();setStatus(${o.id},'enviado')">✅ Enviado</button>`;
    const btnC = `<button class="act-btn cancelar" ${status==='cancelado'?'disabled':''} onclick="event.stopPropagation();setStatus(${o.id},'cancelado')">❌ Cancelado</button>`;
    const btnP = status!=='pendente' ? `<button class="act-btn pendente" onclick="event.stopPropagation();setStatus(${o.id},'pendente')">↩️</button>` : '';
    const btnD = `<button class="act-btn excluir" onclick="event.stopPropagation();excluir(${o.id})" title="Excluir">🗑️</button>`;

    html += `<div class="order-card ${status!=='pendente'?status:''}" onclick="openModal(${o.id})">`;
    html += `<div class="order-num">#${o.id}</div>`;
    html += statusTag;
    html += `<div class="c-nome">👤 ${o.nome}</div>`;
    html += `<div class="c-produto">📦 ${o.produto}</div>`;
    if (valor) html += `<div class="c-valor">💰 ${valor}</div>`;
    html += '<div class="c-meta">';
    if (data)      html += `<span>🗓️ ${data}</span>`;
    if (o.horario) html += `<span>⏰ ${o.horario}</span>`;
    html += '</div>';
    if (o.obs) html += `<div class="c-obs">💬 ${o.obs}</div>`;
    html += `<div class="c-actions">${btnE}${btnC}${btnP}${btnD}</div>`;
    html += '</div>';
  });

  grid.innerHTML = html;
}

// ── Modal ──────────────────────────────────────────────────────────────────
function openModal(id) {
  const o = allOrders.find(x => x.id == id);
  if (!o) return;

  const status = o.status || 'pendente';
  const valor  = fmtVal(o.valor) || '—';
  const data   = fmtDate(o.data_entrega) || '—';
  const colors = { enviado:'#4caf50', cancelado:'#e94560', pendente:'#f9c74f' };
  const labels = { enviado:'✅ Enviado', cancelado:'❌ Cancelado', pendente:'⏳ Pendente' };

  const btnE = `<button class="act-btn enviar"   ${status==='enviado'?'disabled':''} onclick="setStatusModal(${o.id},'enviado')">✅ Enviado</button>`;
  const btnC = `<button class="act-btn cancelar" ${status==='cancelado'?'disabled':''} onclick="setStatusModal(${o.id},'cancelado')">❌ Cancelado</button>`;
  const btnP = status!=='pendente' ? `<button class="act-btn pendente" onclick="setStatusModal(${o.id},'pendente')">↩️ Pendente</button>` : '';

  let html = '';
  html += `<div class="m-row"><div class="m-icon">👤</div><div><div class="m-label">Cliente</div><div class="m-value gold">${o.nome}</div></div></div>`;
  html += `<div class="m-row"><div class="m-icon">📦</div><div><div class="m-label">Produto</div><div class="m-value">${o.produto}</div></div></div>`;
  html += `<div class="m-row"><div class="m-icon">💰</div><div><div class="m-label">Valor</div><div class="m-value green">${valor}</div></div></div>`;
  html += '<hr class="m-divider">';
  html += `<div class="m-row"><div class="m-icon">🗓️</div><div><div class="m-label">Melhor dia para entrega</div><div class="m-value">${data}</div></div></div>`;
  html += `<div class="m-row"><div class="m-icon">⏰</div><div><div class="m-label">Horário</div><div class="m-value">${o.horario||'—'}</div></div></div>`;
  if (o.obs) { html += '<hr class="m-divider">'; html += `<div class="m-row"><div class="m-icon">💬</div><div><div class="m-label">Observações</div><div class="m-value">${o.obs}</div></div></div>`; }
  html += '<hr class="m-divider">';
  html += `<div class="m-row"><div class="m-icon">🔖</div><div><div class="m-label">Status</div><div class="m-value" style="color:${colors[status]};font-weight:bold">${labels[status]}</div></div></div>`;
  html += `<div class="m-row"><div class="m-icon">🕐</div><div><div class="m-label">Recebido em</div><div class="m-value">${o.created_at||''}</div></div></div>`;
  html += `<div class="modal-actions">${btnE}${btnC}${btnP}</div>`;

  document.getElementById('modalContent').innerHTML = html;
  document.getElementById('modalOverlay').classList.add('show');
}

async function setStatusModal(id, status) {
  document.getElementById('modalOverlay').classList.remove('show');
  await setStatus(id, status);
}

// ── Actions ────────────────────────────────────────────────────────────────
async function setStatus(id, status) {
  try {
    await fetch(`api/orders.php?id=${id}`, {
      method: 'PUT',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({status})
    });
    await fetchData(false);
  } catch(e) { alert('Erro: ' + e.message); }
}

async function excluir(id) {
  if (!confirm('Excluir este pedido?')) return;
  try {
    await fetch(`api/orders.php?id=${id}`, { method: 'DELETE' });
    await fetchData(false);
  } catch(e) { alert('Erro: ' + e.message); }
}

// ── Fetch ──────────────────────────────────────────────────────────────────
async function fetchData(isPolling) {
  try {
    const res  = await fetch('api/orders.php?_=' + Date.now());
    const data = await res.json();

    buildSummary(data.stats);

    if (isPolling) {
      const newIds = new Set(data.orders.map(o => o.id));
      const novos  = data.orders.filter(o => !lastIds.has(o.id) && o.status==='pendente');
      if (novos.length) {
        beep(); flashBadge();
        const last = novos[novos.length-1];
        showToast(last.nome + ' pediu ' + last.produto + (last.valor>0?' · '+fmtVal(last.valor):''));
      }
      lastIds = newIds;
    } else {
      lastIds = new Set(data.orders.map(o => o.id));
    }

    allOrders = data.orders;
    renderFiltered();

    document.getElementById('dot').className = 'dot on';
    document.getElementById('statusTxt').textContent =
      '✅ Conectado · ' + data.stats.total + ' pedidos · Atualizado: ' + new Date().toLocaleTimeString('pt-BR');

  } catch(e) {
    document.getElementById('dot').className = 'dot err';
    document.getElementById('statusTxt').textContent = '❌ Erro: ' + e.message;
  }
}

// ── Polling ────────────────────────────────────────────────────────────────
function startPolling() {
  fetchData(false);
  countdown = INTERVAL;
  timer = setInterval(() => {
    countdown--;
    document.getElementById('nextRefresh').textContent = 'Atualiza em ' + countdown + 's';
    if (countdown <= 0) { countdown = INTERVAL; fetchData(true); }
  }, 1000);
}

startPolling();
</script>
</body>
</html>
