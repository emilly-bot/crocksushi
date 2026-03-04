-- ============================================================
-- CrockSushi – Script MySQL para cPanel
-- Execute este script no phpMyAdmin ou MySQL do cPanel
-- ============================================================

CREATE DATABASE IF NOT EXISTS `crocksushi`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `crocksushi`;

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id`           INT(11)        NOT NULL AUTO_INCREMENT,
  `nome`         VARCHAR(120)   NOT NULL,
  `produto`      TEXT           NOT NULL,
  `valor`        DECIMAL(10,2)  DEFAULT 0.00,
  `obs`          TEXT           DEFAULT NULL,
  `data_entrega` DATE           DEFAULT NULL,
  `horario`      VARCHAR(20)    DEFAULT '',
  `status`       VARCHAR(20)    DEFAULT 'pendente',
  `created_at`   DATETIME       DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados de exemplo (opcional, pode apagar)
INSERT INTO `pedidos` (`nome`, `produto`, `valor`, `obs`, `data_entrega`, `horario`, `status`) VALUES
('Ana Silva',     'Combo Sushi 20 peças',    85.00, 'Sem wasabi',          '2026-03-10', '18:00', 'pendente'),
('Carlos Lima',   'Combo Temaki 3 uni',      42.00, '',                    '2026-03-10', '19:00', 'pendente'),
('Julia Souza',   'Combo Sashimi 30 peças',  120.00,'Molho extra à parte', '2026-03-11', '12:00', 'enviado'),
('Pedro Rocha',   'Combo Especial 50 peças', 180.00,'',                    '2026-03-11', '20:00', 'pendente'),
('Mariana Costa', 'Temaki Salmão 5 uni',     65.00, 'Pedir garfo',         '2026-03-12', '13:00', 'cancelado');
