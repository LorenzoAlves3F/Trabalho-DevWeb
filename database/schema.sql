-- =====================================================================
-- Carlore's - Sistema de Gestao de Casa de Festas
-- Script de criacao do banco de dados
-- =====================================================================

CREATE DATABASE IF NOT EXISTS carlores
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE carlores;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS password_resets;
DROP TABLE IF EXISTS pagamentos;
DROP TABLE IF EXISTS reservas;
DROP TABLE IF EXISTS pacote_itens;
DROP TABLE IF EXISTS pacotes;
DROP TABLE IF EXISTS saloes;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS usuarios;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------
-- usuarios: autenticacao central (admin e cliente)
-- ---------------------------------------------------------------------
CREATE TABLE usuarios (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(120) NOT NULL,
    email       VARCHAR(150) NOT NULL,
    senha_hash  VARCHAR(255) NOT NULL,
    tipo        ENUM('admin', 'cliente') NOT NULL DEFAULT 'cliente',
    ativo       TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuarios_email (email),
    INDEX idx_usuarios_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- clientes: perfil 1:1 com usuarios (tipo='cliente')
-- ---------------------------------------------------------------------
CREATE TABLE clientes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT UNSIGNED NOT NULL,
    telefone    VARCHAR(20) NOT NULL,
    cpf         CHAR(11) NOT NULL,
    endereco    VARCHAR(255) NOT NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_clientes_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uq_clientes_usuario (usuario_id),
    UNIQUE KEY uq_clientes_cpf (cpf)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- saloes: espacos disponiveis para locacao
-- ---------------------------------------------------------------------
CREATE TABLE saloes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    capacidade  SMALLINT UNSIGNED NOT NULL,
    descricao   TEXT NULL,
    valor_base  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    foto        VARCHAR(255) NULL,
    ativo       TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_saloes_nome (nome),
    INDEX idx_saloes_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- pacotes: pacotes fechados de festa (Bronze/Prata/Ouro)
-- ---------------------------------------------------------------------
CREATE TABLE pacotes (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(100) NOT NULL,
    descricao   TEXT NULL,
    preco       DECIMAL(10,2) NOT NULL,
    ativo       TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_pacotes_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- pacote_itens: itens fixos inclusos em cada pacote (1:N)
-- ---------------------------------------------------------------------
CREATE TABLE pacote_itens (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pacote_id       INT UNSIGNED NOT NULL,
    descricao_item  VARCHAR(150) NOT NULL,
    ordem           SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT fk_pacote_itens_pacote FOREIGN KEY (pacote_id) REFERENCES pacotes(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pacote_itens_pacote (pacote_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- reservas: tabela central do sistema
-- ---------------------------------------------------------------------
CREATE TABLE reservas (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id          INT UNSIGNED NOT NULL,
    salao_id            INT UNSIGNED NOT NULL,
    pacote_id           INT UNSIGNED NOT NULL,
    data_evento         DATE NOT NULL,
    turno               ENUM('manha', 'tarde', 'noite') NOT NULL,
    tipo_evento         VARCHAR(80) NOT NULL,
    numero_convidados   SMALLINT UNSIGNED NOT NULL,
    status              ENUM('solicitada', 'confirmada', 'cancelada') NOT NULL DEFAULT 'solicitada',
    valor_salao         DECIMAL(10,2) NOT NULL,
    valor_pacote        DECIMAL(10,2) NOT NULL,
    desconto            DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    valor_total         DECIMAL(10,2) GENERATED ALWAYS AS (valor_salao + valor_pacote - desconto) STORED,
    bloqueio_data       DATE GENERATED ALWAYS AS (CASE WHEN status = 'cancelada' THEN NULL ELSE data_evento END) STORED,
    observacoes         TEXT NULL,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_reservas_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_reservas_salao FOREIGN KEY (salao_id) REFERENCES saloes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_reservas_pacote FOREIGN KEY (pacote_id) REFERENCES pacotes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE KEY uq_disponibilidade (salao_id, bloqueio_data),
    INDEX idx_reservas_data (data_evento),
    INDEX idx_reservas_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- pagamentos: pagamentos/parcelas de cada reserva
-- ---------------------------------------------------------------------
CREATE TABLE pagamentos (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reserva_id      INT UNSIGNED NOT NULL,
    valor           DECIMAL(10,2) NOT NULL,
    data_pagamento  DATE NOT NULL,
    forma_pagamento ENUM('dinheiro', 'pix', 'cartao_credito', 'cartao_debito', 'transferencia', 'boleto') NOT NULL,
    tipo            ENUM('sinal', 'parcela', 'quitacao') NOT NULL DEFAULT 'parcela',
    observacoes     VARCHAR(255) NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pagamentos_reserva FOREIGN KEY (reserva_id) REFERENCES reservas(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pagamentos_data (data_pagamento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- password_resets: recuperacao de senha (simulada, sem envio de e-mail real)
-- ---------------------------------------------------------------------
CREATE TABLE password_resets (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT UNSIGNED NOT NULL,
    token       CHAR(64) NOT NULL,
    expira_em   DATETIME NOT NULL,
    usado       TINYINT(1) NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_password_resets_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uq_password_resets_token (token),
    INDEX idx_password_resets_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
