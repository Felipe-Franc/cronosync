-- =====================================================
-- CronoSync - concierge | Schema inicial do MVP
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Tabela: users (usuários do sistema)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(120) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    senha           VARCHAR(255) NOT NULL,        -- hash via password_hash()
    tipo_usuario    ENUM('admin', 'operador') NOT NULL DEFAULT 'operador',
    status          ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: clientes (leads e clientes atendidos)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS clientes (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(150) NOT NULL,
    telefone        VARCHAR(20) NULL,
    whatsapp        VARCHAR(20) NULL,
    email           VARCHAR(150) NULL,
    origem          VARCHAR(80) NULL,             -- ex: indicação, instagram, site
    status          ENUM('novo', 'em_contato', 'agendado', 'atendido', 'perdido', 'inativo') NOT NULL DEFAULT 'novo',
    observacoes     TEXT NULL,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_status (status),
    INDEX idx_telefone (telefone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: servicos (ofertas com duração e valor)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS servicos (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(150) NOT NULL,
    descricao       TEXT NULL,
    duracao_minutos INT UNSIGNED NOT NULL DEFAULT 60,
    valor           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    ativo           TINYINT(1) NOT NULL DEFAULT 1,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: agendamentos (horários e atendimentos)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS agendamentos (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id      INT UNSIGNED NOT NULL,
    servico_id      INT UNSIGNED NOT NULL,
    usuario_id      INT UNSIGNED NOT NULL,        -- responsável pelo atendimento
    data            DATE NOT NULL,
    hora_inicio     TIME NOT NULL,
    hora_fim        TIME NOT NULL,
    status          ENUM('agendado', 'confirmado', 'compareceu', 'nao_compareceu', 'cancelado', 'reagendado') NOT NULL DEFAULT 'agendado',
    valor           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    observacoes     TEXT NULL,
    criado_em       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_agend_cliente  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    CONSTRAINT fk_agend_servico  FOREIGN KEY (servico_id) REFERENCES servicos(id) ON DELETE RESTRICT,
    CONSTRAINT fk_agend_usuario  FOREIGN KEY (usuario_id) REFERENCES users(id)    ON DELETE RESTRICT,
    INDEX idx_data (data),
    INDEX idx_status (status),
    INDEX idx_usuario_data (usuario_id, data)     -- p/ validar conflito de horário
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Tabela: pagamentos (controle financeiro vinculado ao agendamento)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS pagamentos (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    agendamento_id    INT UNSIGNED NOT NULL,
    valor_total       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    valor_pago        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    valor_pendente    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    forma_pagamento   VARCHAR(50) NULL,           -- pix, dinheiro, cartão, transferência
    status_pagamento  ENUM('pendente', 'parcial', 'pago', 'cancelado') NOT NULL DEFAULT 'pendente',
    data_pagamento    DATE NULL,
    criado_em         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pag_agendamento FOREIGN KEY (agendamento_id) REFERENCES agendamentos(id) ON DELETE CASCADE,
    INDEX idx_status (status_pagamento),
    INDEX idx_data_pagamento (data_pagamento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
-- Usuário admin inicial (senha: admin123)
-- -----------------------------------------------------
-- Hash gerado com password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (nome, email, senha, tipo_usuario, status) VALUES
('Administrador', 'admin@cronosync.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'ativo');