
CREATE DATABASE IF NOT EXISTS movix;

USE movix;

-- TABELA DE PERFIS
CREATE TABLE perfis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

-- TABELA DE USUÁRIOS
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL DEFAULT '',
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    id_perfil INT NOT NULL DEFAULT 3,

    FOREIGN KEY (id_perfil) REFERENCES perfis(id)
);

-- TABELA DE TRENS
CREATE TABLE trens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    linha VARCHAR(100) NOT NULL,
    destino VARCHAR(150) NOT NULL,
    status INT NOT NULL DEFAULT 3,
    velocidade INT NOT NULL DEFAULT 0,
    id_usuario INT NULL,

    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- TABELA DE ROTAS
CREATE TABLE rotas (
    id_rota INT AUTO_INCREMENT PRIMARY KEY,
    nome_rota VARCHAR(100) NOT NULL,
    codigo_rota VARCHAR(50) NOT NULL UNIQUE,
    origem VARCHAR(100) NOT NULL,
    destino VARCHAR(100) NOT NULL,
    distancia DECIMAL(8,2) NOT NULL,
    tempo_estimado VARCHAR(50) NOT NULL,
    status ENUM('Ativa', 'Inativa', 'Em manutenção')
        NOT NULL DEFAULT 'Ativa',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- TABELA DE SENSORES
CREATE TABLE sensores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Online'
);

-- TABELA DE ALERTAS
CREATE TABLE alertas (
    id_alerta INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    equipamento VARCHAR(100) NOT NULL,
    gravidade ENUM('Critico', 'Atencao', 'Informativo')
        NOT NULL DEFAULT 'Informativo',
    status ENUM('Pendente', 'Resolvido')
        NOT NULL DEFAULT 'Pendente',
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- CADASTRO DOS PERFIS
INSERT INTO perfis (id, nome) VALUES
(1, 'Administrador'),
(2, 'Operário'),
(3, 'Usuário');
