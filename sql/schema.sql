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
