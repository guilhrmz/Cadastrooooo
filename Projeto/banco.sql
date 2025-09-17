create database tarefasdb;
use tarefasdb;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT NOT NULL,
    setor VARCHAR(50) NOT NULL,
    prioridade ENUM('baixa','media','alta') NOT NULL DEFAULT 'baixa',
    status ENUM('afazer','fazendo','concluido') NOT NULL DEFAULT 'afazer',
    usuario_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);
