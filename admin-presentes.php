<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Lista de Presentes – Admin</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; background: #0d0d0d; color: #f0f0f0; min-height: 100vh; }

header {
  background: linear-gradient(135deg, #2a1a2e, #1e1230);
  padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;
  border-bottom: 2px solid #c4447a; position: sticky; top: 0; z-index: 100;
}
.logo { display: flex; align-items: center; gap: 10px; }
.logo h1 { font-size: 1.4rem; color: #fff; }
.nav-links { display: flex; gap: 10px; }
.nav-btn {
  background: #1e1e3e; color: #fff; border: 1px solid #2a2a5a;
  padding: 7px 16px; border-radius: 8px; text-decoration: none;
  font-size: 0.85rem; cursor: pointer; transition: .2s;
}
.nav-btn:hover { background: #2a2a5a; }
.nav-btn.pink { background: #4a1a2e; border-color: #c4447a; }
.nav-btn.pink:hover { background: #c4447a; }

.page { max-width: 1000px; margin: 0 auto; padding: 24px 20px; }

/* Stats */
.stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-bottom: 24px; }
.stat { background: #1a1a2e; border-radius: 10px; padding: 14px 16px; border-left: 3px solid #c4447a; }
.stat label { display: block; font-size: 0.72rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
.stat strong { font-size: 1.3rem; color: #fff; }

/* Add form */
.add-section { background: #1a1a2e; border-radius: 16px; padding: 22px; margin-bottom: 28px; border: 1px solid #2a2a5a; }
.add-section h2 { color: #f9c74f; font-size: 1.05rem; margin-bottom: 18px; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-grid .full { grid-column: 1/-1; }
.field label { display: block; font-size: 0.72rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px; }
.field input, .field textarea {
  width: 100%; background: #0d0d1a; border: 1px solid #2a2a5a; color: #fff;
  padding: 9px 12px; border-radius: 9px; font-size: 0.9rem; font-family: inherit;
}
.field input:focus, .field textarea:focus { outline: none; border-color: #c4447a; }
.field textarea { min-height: 68px; resize: vertical; }
.add-btn {
  background: #c4447a; color: #fff; border: none; padding: 10px 24px;
  border-radius: 10px; font-size: 0.9rem; font-weight: bold; cursor: pointer; transition: .2s;
}
.add-btn:hover { background: #a03464; }
.add-btn:disabled { opacity: .5; cursor: default; }

/* Table */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
thead th {
  background: #111122; color: #888; font-size: 0.72rem;
  text-transform: uppercase; letter-spacing: .5px; padding: 10px 12px;
  text-align: left; border-bottom: 1px solid #2a2a5a;
}
tbody tr { border-bottom: 1px solid #1a1a3a; }
tbody tr:hover { background: #111122; }
td { padding: 12px; font-size: 0.88rem; vertical-align: middle; }

.badge-disponivel { background: #0d2a0d; color: #4caf50; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; border: 1px solid #4caf50; }
.badge-reservado  { background: #2a1a2e; color: #c4447a; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; border: 1px solid #c4447a; }

.gift-img-thumb {
  width: 44px; height: 44px; border-radius: 8px;
  background: #2a1a2e; display: flex; align-items: center; justify-content: center;
  font-size: 1.4rem; overflow: hidden; flex-shrink: 0;
}
.gift-img-thumb img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }

.act { display: flex; gap: 6px; }
.act-btn { padding: 5px 10px; border-radius: 7px; border: none; cursor: pointer; font-size: 0.75rem; font-weight: bold; transition: .2s; }
.act-btn.liberar  { background: #1a2a3a; color: #74b4f4; border: 1px solid #74b4f4; }
.act-btn.liberar:hover  { background: #74b4f4; color: #000; }
.act-btn.excluir  { background: #2a1a1a; color: #e94560; border: 1px solid #e94560; }
.act-btn.excluir:hover  { background: #e94560; color: #fff; }

.empty-row { text-align: center; padding: 40px; color: #555; }

.toast {
  position: fixed; bottom: 24px; right: 20px;
  background: #1a1a2e; border-radius: 14px; padding: 14px 18px;
  z-index: 999; display: none; max-width: 310px;
  box-shadow: 0 4px 24px rgba(0,0,0,.4); font-size: 0.88rem;
  border-left: 4px solid #4caf50;
}
.toast.show { display: block; }
.toast.err { border-left-color: #e94560; }
</style>
</head>
<body>

<header>
  <div class="logo">
    <span style="font-size:1.6rem">💍</span>
    <h1>Admin – Lista de Presentes</h1>
  </div>
  <div class="nav-links">
    <a class="nav-btn pink" href="lista.php" target="_blank">👁️ Ver lista pública</a>
    <a class="nav-btn" href="index.php">🍣 Painel CrockSushi</a>
  </div>
</header>

<div class="page">

  <!-- Stats -->
  <div class="stats">
    <div class="stat"><label>🎁 Total</label><strong id="sTotal">0</strong></div>
    <div class="stat" style="border-left-color:#4caf50"><label>✅ Disponíveis</label><strong id="sDisp">0</strong></div>
    <div class="stat" style="border-left-color:#f9c74f"><label>🔒 Reservados</label><strong id="sRes">0</strong></div>
  </div>

  <!-- Adicionar presente -->
  <div class="add-section">
    <h2>➕ Adicionar Presente</h2>
    <div class="form-grid">
      <div class="field full">
        <label>Nome do presente *</label>
        <input type="text" id="fNome" placeholder="Ex: Jogo de panelas antiaderentes" maxlength="200" />
      </div>
      <div class="field full">
        <label>Descrição (opcional)</label>
        <textarea id="fDesc" placeholder="Ex: 5 peças, cabo de silicone, inclui frigideira..."></textarea>
      </div>
      <div class="field">
        <label>Valor aproximado (R$)</label>
        <input type="text" id="fValor" placeholder="Ex: 250,00" />
      </div>
      <div class="field">
        <label>URL da imagem (opcional)</label>
        <input type="url" id="fImg" placeholder="https://..." />
      </div>
    </div>
    <br>
    <button class="add-btn" id="addBtn" onclick="adicionarPresente()">➕ Adicionar à lista</button>
    <span id="addMsg" style="margin-left:12px;font-size:0.82rem;color:#888"></span>
  </div>

  <!-- Tabela de presentes -->
  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Imagem</th>
          <th>Nome</th>
          <th>Descrição</th>
          <th>Valor</th>
          <th>Status</th>
          <th>Reservado por</th>
          <th>Forma</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody id="giftsBody">
        <tr><td colspan="9" class="empty-row">Carregando...</td></tr>
      </tbody>
    </table>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
let gifts = [];

function showToast(msg, ok) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'toast show' + (ok === false ? ' err' : '');
  clearTimeout(t._to);
  t._to = setTimeout(function(){ t.classList.remove('show'); }, 3500);
}

function escHtml(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function fmtValor(v) {
  const n = parseFloat(v);
  if (!v || isNaN(n) || n === 0) return '—';
  return 'R$ ' + n.toFixed(2).replace('.',',');
}

async function loadGifts() {
  try {
    const res  = await fetch('api/gifts.php?_=' + Date.now());
    const data = await res.json();
    gifts = data.gifts || [];
    renderTable();
  } catch(e) {
    showToast('Erro ao carregar: ' + e.message, false);
  }
}

function renderTable() {
  const total = gifts.length;
  const res   = gifts.filter(function(g){ return g.reservado == 1; }).length;
  const disp  = total - res;
  document.getElementById('sTotal').textContent = total;
  document.getElementById('sDisp').textContent  = disp;
  document.getElementById('sRes').textContent   = res;

  const body = document.getElementById('giftsBody');
  if (!total) {
    body.innerHTML = '<tr><td colspan="9" class="empty-row">Nenhum presente cadastrado ainda.</td></tr>';
    return;
  }

  let html = '';
  gifts.forEach(function(g) {
    const reservado = g.reservado == 1;
    let imgHtml = '<div class="gift-img-thumb">';
    if (g.imagem_url) {
      imgHtml += '<img src="' + escHtml(g.imagem_url) + '" onerror="this.outerHTML=\'<span>🎁</span>\'">';
    } else {
      imgHtml += '<span>🎁</span>';
    }
    imgHtml += '</div>';

    const forma = g.forma_pagamento === 'pix' ? '📱 PIX' :
                  g.forma_pagamento === 'presencial' ? '🎁 Presencial' : '—';

    html += '<tr>';
    html += '<td style="color:#666">' + g.id + '</td>';
    html += '<td>' + imgHtml + '</td>';
    html += '<td><strong>' + escHtml(g.nome) + '</strong></td>';
    html += '<td style="color:#888;max-width:180px">' + escHtml(g.descricao || '—') + '</td>';
    html += '<td>' + fmtValor(g.valor) + '</td>';
    html += '<td>' + (reservado
      ? '<span class="badge-reservado">Reservado</span>'
      : '<span class="badge-disponivel">Disponível</span>') + '</td>';
    html += '<td>' + (g.reservado_por ? escHtml(g.reservado_por) : '—') + '</td>';
    html += '<td>' + forma + '</td>';
    html += '<td><div class="act">';
    if (reservado) {
      html += '<button class="act-btn liberar" onclick="liberarReserva(' + g.id + ')">↩️ Liberar</button>';
    }
    html += '<button class="act-btn excluir" onclick="excluirPresente(' + g.id + ')">🗑️</button>';
    html += '</div></td>';
    html += '</tr>';
  });

  body.innerHTML = html;
}

async function adicionarPresente() {
  const nome  = document.getElementById('fNome').value.trim();
  const desc  = document.getElementById('fDesc').value.trim();
  const valor = document.getElementById('fValor').value.trim();
  const img   = document.getElementById('fImg').value.trim();
  const btn   = document.getElementById('addBtn');
  const msg   = document.getElementById('addMsg');

  if (!nome) { showToast('Informe o nome do presente!', false); return; }

  btn.disabled = true;
  msg.textContent = 'Salvando...';

  try {
    const res = await fetch('api/gifts.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nome, descricao: desc, valor, imagem_url: img })
    });
    const data = await res.json();
    if (res.ok && data.success) {
      document.getElementById('fNome').value  = '';
      document.getElementById('fDesc').value  = '';
      document.getElementById('fValor').value = '';
      document.getElementById('fImg').value   = '';
      msg.textContent = '';
      showToast('Presente adicionado!');
      await loadGifts();
    } else {
      throw new Error(data.error || 'Erro ao adicionar');
    }
  } catch(e) {
    showToast('Erro: ' + e.message, false);
    msg.textContent = '';
  }

  btn.disabled = false;
}

async function liberarReserva(id) {
  if (!confirm('Liberar a reserva deste presente?')) return;
  try {
    const res = await fetch('api/gifts.php?id=' + id, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ reservado: 0 })
    });
    const data = await res.json();
    if (res.ok && data.success) { showToast('Reserva liberada!'); await loadGifts(); }
    else throw new Error(data.error || 'Erro');
  } catch(e) {
    showToast('Erro: ' + e.message, false);
  }
}

async function excluirPresente(id) {
  if (!confirm('Excluir este presente da lista?')) return;
  try {
    const res = await fetch('api/gifts.php?id=' + id, { method: 'DELETE' });
    const data = await res.json();
    if (res.ok && data.success) { showToast('Presente removido.'); await loadGifts(); }
    else throw new Error(data.error || 'Erro');
  } catch(e) {
    showToast('Erro: ' + e.message, false);
  }
}

// Enter no campo nome
document.getElementById('fNome').addEventListener('keydown', function(e){
  if (e.key === 'Enter') document.getElementById('fValor').focus();
});

loadGifts();
</script>
</body>
</html>
