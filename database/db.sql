
-- =========================================
-- BANCO DE DADOS MOVIX
-- Gestão Ferroviária Inteligente
-- =========================================

CREATE DATABASE IF NOT EXISTS movix;

USE movix;

-- =========================================
-- TABELA DE PERFIS
-- =========================================

CREATE TABLE IF NOT EXISTS perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

INSERT INTO perfis (id, nome) VALUES
(1, 'Administrador'),
(2, 'Operário'),
(3, 'Usuário')
ON DUPLICATE KEY UPDATE
nome = VALUES(nome);


-- =========================================
-- TABELA DE USUÁRIOS
-- =========================================

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL DEFAULT '',
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    id_perfil INT NOT NULL DEFAULT 3,

    CONSTRAINT fk_usuarios_perfis
        FOREIGN KEY (id_perfil)
        REFERENCES perfis(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB;


-- =========================================
-- TABELA DE ROTAS
-- Cada rota pertence a um usuário responsável
-- =========================================

CREATE TABLE IF NOT EXISTS rotas (
    id_rota INT AUTO_INCREMENT PRIMARY KEY,
    nome_rota VARCHAR(100) NOT NULL,
    codigo_rota VARCHAR(50) NOT NULL UNIQUE,
    origem VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    distancia DECIMAL(8,2) NOT NULL,
    tempo_estimado VARCHAR(50) NOT NULL,

    status ENUM(
        'Ativa',
        'Inativa',
        'Em manutenção'
    ) NOT NULL DEFAULT 'Ativa',

    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    id_usuario INT NOT NULL,

    CONSTRAINT fk_rotas_usuarios
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB;


-- =========================================
-- TABELA DE TRENS
-- Cada trem pertence a uma rota
-- =========================================

CREATE TABLE IF NOT EXISTS trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    linha VARCHAR(100) NOT NULL,
    destino VARCHAR(150) NOT NULL,

    status INT NOT NULL DEFAULT 3,
    velocidade INT NOT NULL DEFAULT 0,

    id_rota INT NOT NULL,

    CONSTRAINT fk_trens_rotas
        FOREIGN KEY (id_rota)
        REFERENCES rotas(id_rota)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB;


-- =========================================
-- TABELA DE SENSORES
-- Cada sensor pode estar associado a um trem
-- =========================================

CREATE TABLE IF NOT EXISTS sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Online',

    id_trem INT NULL,

    CONSTRAINT fk_sensores_trens
        FOREIGN KEY (id_trem)
        REFERENCES trens(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB;


-- =========================================
-- CONSULTA PARA CONFERIR OS RELACIONAMENTOS
-- =========================================

SELECT
    u.nome AS usuario_responsavel,
    p.nome AS perfil,
    r.nome_rota,
    r.codigo_rota,
    t.codigo AS codigo_trem,
    t.nome AS nome_trem,
    s.codigo AS codigo_sensor,
    s.nome AS nome_sensor

FROM usuarios u

INNER JOIN perfis p
    ON u.id_perfil = p.id

LEFT JOIN rotas r
    ON u.id = r.id_usuario

LEFT JOIN trens t
    ON r.id_rota = t.id_rota

LEFT JOIN sensores s
    ON t.id = s.id_trem;
