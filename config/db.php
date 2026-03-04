<?php
// ─── CONFIGURAÇÃO DO BANCO ───────────────────────────────────────────────────
// LOCAL  → SQLite (sem instalação)
// cPANEL → mude DB_DRIVER para 'mysql' e preencha as credenciais abaixo
// ─────────────────────────────────────────────────────────────────────────────

define('DB_DRIVER', 'sqlite');   // 'sqlite' ou 'mysql'

// MySQL (cPanel)
define('DB_HOST', 'localhost');
define('DB_NAME', 'crocksushi');
define('DB_USER', 'root');
define('DB_PASS', '');

// SQLite
define('DB_SQLITE_PATH', __DIR__ . '/../database.sqlite');

function getDB(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    if (DB_DRIVER === 'mysql') {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } else {
        $pdo = new PDO('sqlite:' . DB_SQLITE_PATH, null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $pdo->exec('PRAGMA journal_mode=WAL');
    }

    return $pdo;
}

function initDB(): void {
    $db = getDB();
    $db->exec("
        CREATE TABLE IF NOT EXISTS pedidos (
            id           INTEGER PRIMARY KEY AUTOINCREMENT,
            nome         VARCHAR(120) NOT NULL,
            produto      TEXT         NOT NULL,
            valor        DECIMAL(10,2) DEFAULT 0,
            obs          TEXT         DEFAULT '',
            data_entrega DATE         DEFAULT NULL,
            horario      VARCHAR(20)  DEFAULT '',
            status       VARCHAR(20)  DEFAULT 'pendente',
            created_at   DATETIME     DEFAULT CURRENT_TIMESTAMP
        )
    ");
}
