<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

initDB();
$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];

function json_out(mixed $data, int $code = 200): never {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function sanitize(string $v): string {
    return htmlspecialchars(strip_tags(trim($v)), ENT_QUOTES, 'UTF-8');
}

// ─── GET /api/gifts.php ───────────────────────────────────────────────────────
if ($method === 'GET') {
    $rows = $db->query("SELECT * FROM presentes ORDER BY id ASC")->fetchAll();
    json_out(['gifts' => $rows]);
}

// ─── POST /api/gifts.php ──────────────────────────────────────────────────────
// Adicionar presente (admin) OU reservar presente (convidado)
if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $action = $body['action'] ?? 'add';

    // Reservar presente
    if ($action === 'reservar') {
        $id             = (int)($body['id'] ?? 0);
        $reservado_por  = sanitize($body['reservado_por'] ?? '');
        $forma_pagamento = sanitize($body['forma_pagamento'] ?? '');

        if (!$id || !$reservado_por) {
            json_out(['error' => 'ID e nome são obrigatórios'], 400);
        }
        if (!in_array($forma_pagamento, ['pix', 'presencial'])) {
            json_out(['error' => 'Forma de pagamento inválida'], 400);
        }

        // Verifica se já está reservado
        $row = $db->query("SELECT * FROM presentes WHERE id = $id")->fetch();
        if (!$row) json_out(['error' => 'Presente não encontrado'], 404);
        if ($row['reservado']) json_out(['error' => 'Este presente já foi escolhido por outra pessoa'], 409);

        $db->prepare("
            UPDATE presentes
            SET reservado=1, reservado_por=:rp, forma_pagamento=:fp, reservado_em=CURRENT_TIMESTAMP
            WHERE id=:id
        ")->execute([':rp' => $reservado_por, ':fp' => $forma_pagamento, ':id' => $id]);

        $updated = $db->query("SELECT * FROM presentes WHERE id = $id")->fetch();
        json_out(['success' => true, 'gift' => $updated]);
    }

    // Adicionar presente (admin)
    $nome       = sanitize($body['nome'] ?? '');
    $descricao  = sanitize($body['descricao'] ?? '');
    $valor      = (float)str_replace(',', '.', preg_replace('/[^\d.,]/', '', $body['valor'] ?? '0'));
    $imagem_url = sanitize($body['imagem_url'] ?? '');

    if (!$nome) json_out(['error' => 'Nome do presente é obrigatório'], 400);

    $db->prepare("
        INSERT INTO presentes (nome, descricao, valor, imagem_url)
        VALUES (:nome, :descricao, :valor, :imagem_url)
    ")->execute([':nome' => $nome, ':descricao' => $descricao, ':valor' => $valor, ':imagem_url' => $imagem_url]);

    $id  = $db->lastInsertId();
    $row = $db->query("SELECT * FROM presentes WHERE id = $id")->fetch();
    json_out(['success' => true, 'gift' => $row], 201);
}

// ─── PUT /api/gifts.php?id=X ─────────────────────────────────────────────────
if ($method === 'PUT') {
    $id   = (int)($_GET['id'] ?? 0);
    $body = json_decode(file_get_contents('php://input'), true) ?? [];
    if (!$id) json_out(['error' => 'ID inválido'], 400);

    // Liberar reserva
    if (isset($body['reservado']) && $body['reservado'] == 0) {
        $db->prepare("
            UPDATE presentes SET reservado=0, reservado_por='', forma_pagamento='', reservado_em=NULL WHERE id=:id
        ")->execute([':id' => $id]);
        $row = $db->query("SELECT * FROM presentes WHERE id = $id")->fetch();
        json_out(['success' => true, 'gift' => $row]);
    }

    // Editar campos
    $fields = [];
    $params = [':id' => $id];
    foreach (['nome','descricao','imagem_url'] as $f) {
        if (isset($body[$f])) { $fields[] = "$f = :$f"; $params[":$f"] = sanitize($body[$f]); }
    }
    if (isset($body['valor'])) {
        $fields[] = 'valor = :valor';
        $params[':valor'] = (float)str_replace(',', '.', preg_replace('/[^\d.,]/', '', $body['valor']));
    }
    if ($fields) {
        $db->prepare("UPDATE presentes SET " . implode(', ', $fields) . " WHERE id = :id")->execute($params);
    }
    $row = $db->query("SELECT * FROM presentes WHERE id = $id")->fetch();
    json_out(['success' => true, 'gift' => $row]);
}

// ─── DELETE /api/gifts.php?id=X ──────────────────────────────────────────────
if ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) json_out(['error' => 'ID inválido'], 400);
    $db->prepare("DELETE FROM presentes WHERE id = :id")->execute([':id' => $id]);
    json_out(['success' => true]);
}

json_out(['error' => 'Método não suportado'], 405);
