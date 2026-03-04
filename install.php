<?php
require_once __DIR__ . '/config/db.php';

try {
    initDB();

    // Insere dados de exemplo se a tabela estiver vazia
    $db = getDB();
    $count = $db->query('SELECT COUNT(*) FROM pedidos')->fetchColumn();

    if ((int)$count === 0) {
        $stmt = $db->prepare("
            INSERT INTO pedidos (nome, produto, valor, obs, data_entrega, horario, status)
            VALUES (:nome, :produto, :valor, :obs, :data_entrega, :horario, :status)
        ");

        $exemplos = [
            ['Ana Silva',    'Combo Sushi 20 peças',   85.00, 'Sem wasabi',          '2026-03-10', '18:00', 'pendente'],
            ['Carlos Lima',  'Combo Temaki 3 uni',     42.00, '',                    '2026-03-10', '19:00', 'pendente'],
            ['Julia Souza',  'Combo Sashimi 30 peças', 120.00,'Molho extra à parte', '2026-03-11', '12:00', 'enviado' ],
            ['Pedro Rocha',  'Combo Especial 50 peças',180.00,'',                    '2026-03-11', '20:00', 'pendente'],
            ['Mariana Costa','Temaki Salmão 5 uni',    65.00, 'Pedir garfo',         '2026-03-12', '13:00', 'cancelado'],
        ];

        foreach ($exemplos as $e) {
            $stmt->execute([
                ':nome'        => $e[0],
                ':produto'     => $e[1],
                ':valor'       => $e[2],
                ':obs'         => $e[3],
                ':data_entrega'=> $e[4],
                ':horario'     => $e[5],
                ':status'      => $e[6],
            ]);
        }
        echo "<p>✅ Banco criado e populado com dados de exemplo!</p>";
    } else {
        echo "<p>✅ Banco já existe ({$count} pedidos).</p>";
    }

    echo "<p><a href='index.php'>→ Ir para o Dashboard</a></p>";
    echo "<p><a href='pedido.php'>→ Ver Formulário de Pedidos</a></p>";

} catch (Exception $e) {
    echo "<p>❌ Erro: " . htmlspecialchars($e->getMessage()) . "</p>";
}
