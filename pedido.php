<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CrockSushi – Fazer Pedido</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Segoe UI', sans-serif; background: #0d0d0d; color: #f0f0f0; min-height: 100vh; display: flex; flex-direction: column; align-items: center; padding: 30px 16px; }

header { width: 100%; max-width: 520px; display: flex; align-items: center; gap: 12px; margin-bottom: 30px; }
.logo-icon { font-size: 2.4rem; }
header h1 { font-size: 1.6rem; color: #fff; }
header p  { font-size: 0.85rem; color: #888; }

.card { background: #1a1a2e; border-radius: 18px; padding: 28px; width: 100%; max-width: 520px; border: 1px solid #2a2a5a; }
.card h2 { color: #f9c74f; font-size: 1.1rem; margin-bottom: 22px; }

.field { margin-bottom: 18px; }
.field label { display: block; font-size: 0.78rem; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
.field input, .field textarea, .field select {
  width: 100%; background: #0d0d1a; border: 1px solid #2a2a5a; color: #fff;
  padding: 10px 14px; border-radius: 10px; font-size: 0.95rem; font-family: inherit;
}
.field input:focus, .field textarea:focus, .field select:focus {
  outline: none; border-color: #e94560;
}
.field textarea { resize: vertical; min-height: 80px; }
.row2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

.btn { width: 100%; background: #e94560; color: #fff; border: none; padding: 13px; border-radius: 12px; font-size: 1rem; font-weight: bold; cursor: pointer; transition: .2s; margin-top: 6px; }
.btn:hover { background: #c73652; }
.btn:disabled { opacity: .5; cursor: default; }

.success-box { display: none; background: #0d2a0d; border: 1px solid #4caf50; border-radius: 14px; padding: 22px; text-align: center; margin-top: 20px; }
.success-box span { font-size: 2.5rem; display: block; margin-bottom: 10px; }
.success-box h3 { color: #4caf50; margin-bottom: 8px; }
.success-box p  { color: #ccc; font-size: 0.9rem; }
.novo-btn { display: inline-block; margin-top: 14px; background: #1e1e3e; color: #fff; border: none; padding: 10px 24px; border-radius: 10px; cursor: pointer; font-size: 0.9rem; }

.error-msg { color: #e94560; font-size: 0.85rem; margin-top: 8px; display: none; }
</style>
</head>
<body>

<header>
  <div class="logo-icon">🥚🍣</div>
  <div>
    <h1>CrockSushi</h1>
    <p>Faça seu pedido abaixo</p>
  </div>
</header>

<div class="card" id="formCard">
  <h2>📋 Novo Pedido</h2>

  <div class="field">
    <label>👤 Seu nome *</label>
    <input type="text" id="nome" placeholder="Ex: João Silva" maxlength="120">
  </div>

  <div class="field">
    <label>📦 O que você quer pedir? *</label>
    <textarea id="produto" placeholder="Ex: Combo Sushi 20 peças, Temaki Salmão..."></textarea>
  </div>

  <div class="field">
    <label>💰 Valor combinado (R$)</label>
    <input type="text" id="valor" placeholder="Ex: 85,00">
  </div>

  <div class="row2">
    <div class="field">
      <label>🗓️ Melhor dia para entrega</label>
      <input type="date" id="data_entrega">
    </div>
    <div class="field">
      <label>⏰ Horário preferido</label>
      <input type="text" id="horario" placeholder="Ex: 18:00 ou 19h-20h">
    </div>
  </div>

  <div class="field">
    <label>💬 Observações</label>
    <textarea id="obs" placeholder="Alergias, preferências, endereço de entrega..."></textarea>
  </div>

  <p class="error-msg" id="errMsg"></p>
  <button class="btn" id="submitBtn" onclick="enviarPedido()">🛒 Enviar Pedido</button>
</div>

<div class="success-box" id="successBox">
  <span>🎉</span>
  <h3>Pedido recebido!</h3>
  <p>Entraremos em contato em breve para confirmar.</p>
  <button class="novo-btn" onclick="novoPedido()">➕ Fazer outro pedido</button>
</div>

<script>
async function enviarPedido() {
  const nome    = document.getElementById('nome').value.trim();
  const produto = document.getElementById('produto').value.trim();
  const valor   = document.getElementById('valor').value.trim();
  const data    = document.getElementById('data_entrega').value;
  const horario = document.getElementById('horario').value.trim();
  const obs     = document.getElementById('obs').value.trim();
  const err     = document.getElementById('errMsg');
  const btn     = document.getElementById('submitBtn');

  err.style.display = 'none';

  if (!nome)    { err.textContent = '⚠️ Informe seu nome.';    err.style.display='block'; return; }
  if (!produto) { err.textContent = '⚠️ Informe o pedido.';   err.style.display='block'; return; }

  btn.disabled = true;
  btn.textContent = 'Enviando...';

  try {
    const res = await fetch('api/orders.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nome, produto, valor, obs, data_entrega: data, horario })
    });
    const data2 = await res.json();

    if (res.ok && data2.success) {
      document.getElementById('formCard').style.display  = 'none';
      document.getElementById('successBox').style.display = 'block';
    } else {
      throw new Error(data2.error || 'Erro ao enviar');
    }
  } catch(e) {
    err.textContent = '❌ ' + e.message;
    err.style.display = 'block';
    btn.disabled = false;
    btn.textContent = '🛒 Enviar Pedido';
  }
}

function novoPedido() {
  ['nome','produto','valor','data_entrega','horario','obs'].forEach(function(id){
    document.getElementById(id).value = '';
  });
  document.getElementById('formCard').style.display  = 'block';
  document.getElementById('successBox').style.display = 'none';
  document.getElementById('submitBtn').disabled = false;
  document.getElementById('submitBtn').textContent = '🛒 Enviar Pedido';
}
</script>
</body>
</html>
