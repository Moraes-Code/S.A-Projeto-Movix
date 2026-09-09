-- Banco do sistema Movix
CREATE DATABASE IF NOT EXISTS movix;
USE movix;

-- Usuários
CREATE TABLE usuarios (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuário para realizar os testes do sistema
INSERT INTO usuarios (nome, email, telefone, senha)
VALUES
('Administrador', 'admin@movix.com', '(47) 99999-9999', 'senha123');