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

// ─── GET /api/orders.php ─────────────────────────────────────────────────────
if ($method === 'GET') {
    $stmt = $db->query("SELECT * FROM pedidos ORDER BY
        CASE status WHEN 'pendente' THEN 0 WHEN 'enviado' THEN 1 ELSE 2 END,
        data_entrega ASC, id ASC
    ");
    $rows = $stmt->fetchAll();

    // Stats
    $total     = count($rows);
    $pendentes = count(array_filter($rows, fn($r) => $r['status'] === 'pendente'));
    $enviados  = count(array_filter($rows, fn($r) => $r['status'] === 'enviado'));
    $cancelados= count(array_filter($rows, fn($r) => $r['status'] === 'cancelado'));
    $fat       = array_sum(array_map(fn($r) => $r['status'] === 'enviado' ? (float)$r['valor'] : 0, $rows));
    $ticket    = $enviados > 0 ? $fat / $enviados : 0;

    json_out([
        'orders' => $rows,
        'stats'  => compact('total','pendentes','enviados','cancelados','fat','ticket'),
    ]);
}

// ─── POST /api/orders.php ─────────────────────────────────────────────────────
if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?? $_POST;

    $nome    = sanitize($body['nome']    ?? '');
    $produto = sanitize($body['produto'] ?? '');
    $valor   = (float)str_replace(',', '.', preg_replace('/[^\d.,]/', '', $body['valor'] ?? '0'));
    $obs     = sanitize($body['obs']     ?? '');
    $data    = sanitize($body['data_entrega'] ?? '');
    $horario = sanitize($body['horario'] ?? '');

    if (!$nome || !$produto) {
        json_out(['error' => 'Nome e produto são obrigatórios'], 400);
    }

    $stmt = $db->prepare("
        INSERT INTO pedidos (nome, produto, valor, obs, data_entrega, horario)
        VALUES (:nome, :produto, :valor, :obs, :data_entrega, :horario)
    ");
    $stmt->execute([
        ':nome'        => $nome,
        ':produto'     => $produto,
        ':valor'       => $valor,
        ':obs'         => $obs,
        ':data_entrega'=> $data ?: null,
        ':horario'     => $horario,
    ]);

    $id = $db->lastInsertId();
    $row = $db->query("SELECT * FROM pedidos WHERE id = $id")->fetch();
    json_out(['success' => true, 'order' => $row], 201);
}

// ─── PUT /api/orders.php?id=X ────────────────────────────────────────────────
if ($method === 'PUT') {
    $id   = (int)($_GET['id'] ?? 0);
    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    if (!$id) json_out(['error' => 'ID inválido'], 400);

    $allowed = ['pendente', 'enviado', 'cancelado'];
    $status  = $body['status'] ?? '';

    // Atualizar status
    if ($status) {
        if (!in_array($status, $allowed)) json_out(['error' => 'Status inválido'], 400);
        $db->prepare("UPDATE pedidos SET status = :s WHERE id = :id")
           ->execute([':s' => $status, ':id' => $id]);
        $row = $db->query("SELECT * FROM pedidos WHERE id = $id")->fetch();
        json_out(['success' => true, 'order' => $row]);
    }

    // Atualizar campos do pedido
    $fields = [];
    $params = [':id' => $id];
    foreach (['nome','produto','obs','horario'] as $f) {
        if (isset($body[$f])) { $fields[] = "$f = :$f"; $params[":$f"] = sanitize($body[$f]); }
    }
    if (isset($body['valor'])) {
        $fields[] = 'valor = :valor';
        $params[':valor'] = (float)str_replace(',', '.', preg_replace('/[^\d.,]/', '', $body['valor']));
    }
    if (isset($body['data_entrega'])) {
        $fields[] = 'data_entrega = :data_entrega';
        $params[':data_entrega'] = sanitize($body['data_entrega']) ?: null;
    }

    if ($fields) {
        $db->prepare("UPDATE pedidos SET " . implode(', ', $fields) . " WHERE id = :id")
           ->execute($params);
    }

    $row = $db->query("SELECT * FROM pedidos WHERE id = $id")->fetch();
    json_out(['success' => true, 'order' => $row]);
}

// ─── DELETE /api/orders.php?id=X ─────────────────────────────────────────────
if ($method === 'DELETE') {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) json_out(['error' => 'ID inválido'], 400);
    $db->prepare("DELETE FROM pedidos WHERE id = :id")->execute([':id' => $id]);
    json_out(['success' => true]);
}

json_out(['error' => 'Método não suportado'], 405);
