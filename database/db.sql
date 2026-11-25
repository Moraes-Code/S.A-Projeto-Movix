
-- ============================================
-- MOVIX - GESTAO FERROVIARIA INTELIGENTE
-- BANCO DE DADOS COMPLETO
-- ESTRUTURA CORRIGIDA
-- ============================================

CREATE DATABASE IF NOT EXISTS movix
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE movix;

-- ============================================
-- REMOVER TABELAS
-- ============================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS sensores;
DROP TABLE IF EXISTS rotas;
DROP TABLE IF EXISTS trens;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS perfis;

SET FOREIGN_KEY_CHECKS = 1;


-- ============================================
-- TABELA: PERFIS
-- ============================================

CREATE TABLE perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO perfis (id, nome) VALUES
(1, 'Administrador'),
(2, 'Operário'),
(3, 'Usuário');


-- ============================================
-- TABELA: USUARIOS
-- ============================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    id_perfil INT NOT NULL DEFAULT 3,

    INDEX idx_usuarios_perfil (id_perfil),

    CONSTRAINT fk_usuarios_perfis
        FOREIGN KEY (id_perfil)
        REFERENCES perfis(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================
-- TABELA: TRENS
-- Cada trem pertence a um usuario
-- ============================================

CREATE TABLE trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    linha VARCHAR(100) NOT NULL,
    destino VARCHAR(150) NOT NULL,

    status INT NOT NULL DEFAULT 3,
    velocidade INT NOT NULL DEFAULT 0,

    id_usuario INT NOT NULL,

    INDEX idx_trens_usuario (id_usuario),

    CONSTRAINT fk_trens_usuarios
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================
-- TABELA: ROTAS
-- Cada rota pertence a um trem
-- ============================================

CREATE TABLE rotas (
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

    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    id_trem INT NOT NULL,

    INDEX idx_rotas_trem (id_trem),

    CONSTRAINT fk_rotas_trens
        FOREIGN KEY (id_trem)
        REFERENCES trens(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================
-- TABELA: SENSORES
-- Cada sensor pertence a uma rota
-- ============================================

CREATE TABLE sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,

    status VARCHAR(20) NOT NULL DEFAULT 'Online',

    id_rota INT NOT NULL,

    INDEX idx_sensores_rota (id_rota),

    CONSTRAINT fk_sensores_rotas
        FOREIGN KEY (id_rota)
        REFERENCES rotas(id_rota)
        ON UPDATE CASCADE
        ON DELETE RESTRICT

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ============================================
-- CONSULTA: VISUALIZAR TODOS OS RELACIONAMENTOS
-- ============================================

SELECT
    u.id AS id_usuario,
    u.nome AS usuario_responsavel,
    p.nome AS perfil,

    t.id AS id_trem,
    t.codigo AS codigo_trem,
    t.nome AS nome_trem,

    r.id_rota,
    r.nome_rota,
    r.codigo_rota,

    s.id AS id_sensor,
    s.codigo AS codigo_sensor,
    s.nome AS nome_sensor

FROM usuarios u

INNER JOIN perfis p
    ON u.id_perfil = p.id

LEFT JOIN trens t
    ON u.id = t.id_usuario

LEFT JOIN rotas r
    ON t.id = r.id_trem

LEFT JOIN sensores s
    ON r.id_rota = s.id_rota

ORDER BY u.id, t.id, r.id_rota, s.id;


-- ============================================
-- CONFERIR AS TABELAS
-- ============================================

SHOW TABLES;

DESCRIBE usuarios;
DESCRIBE trens;
DESCRIBE rotas;
DESCRIBE sensores;
DESCRIBE perfis;
