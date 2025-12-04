CREATE DATABASE IF NOT EXISTS sistema_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_auth;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  perfil ENUM('admin','user') NOT NULL DEFAULT 'user',
  nome VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- e-mail: admin@example.com
-- senha: Senha123!
INSERT INTO usuarios (email, senha_hash, perfil) VALUES (
  'admin@example.com',
  '$2y$10$HFYs3/01y0Z/KwpQLtmff.O3afzZkQtu.ZIG5oWPkkXxQBLp7VkAy',
  'admin'
);

CREATE TABLE IF NOT EXISTS password_reset (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  expira_em DATETIME NOT NULL,
  token_hash VARCHAR(255) NOT NULL,
  INDEX idx_token(token_hash),

  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE

)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NULL,
  acao VARCHAR(255) NOT NULL,
  detalhes TEXT NOT NULL,
  data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

UPDATE usuarios SET nome = 'Administrador' WHERE email = 'admin@example.com';

CREATE TABLE IF NOT EXISTS categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  categoria_id INT NULL,
  preco DECIMAL(10,2) NOT NULL,
  estoque INT NOT NULL,
  image_url VARCHAR(255) NULL,
  FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE produtos CHANGE name nome VARCHAR(255) NOT NULL;

INSERT INTO categorias(nome) VALUES
('Camisetas'),
('Calças'),
('Shorts'),
('Tênis'),
('Acessórios');

ALTER TABLE produtos ADD descricao TEXT NULL AFTER nome;
Alter TABLE produtos CHANGE image_url imagem_url VARCHAR(255) NULL;
ALTER TABLE produtos ADD data_lancamento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
UPDATE produtos SET data_lancamento = NOW() WHERE data_lancamento IS NULL;

ALTER TABLE produtos ADD vendas INT NOT NULL DEFAULT 0;

UPDATE produtos SET vendas = 10 WHERE id = 1;
UPDATE produtos SET vendas = 5 WHERE id = 2;
UPDATE produtos SET vendas = 7 WHERE id = 3;
