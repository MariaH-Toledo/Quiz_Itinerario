CREATE DATABASE quiz;
USE quiz;

CREATE TABLE usuario(
id_usuario INT PRIMARY KEY AUTO_INCREMENT,
nome_completo VARCHAR(150) NOT NULL,
nome_usuario VARCHAR(100) NOT NULL UNIQUE,
senha_hash VARCHAR(80) NOT NULL,
data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categoria(
id_categoria INT PRIMARY KEY AUTO_INCREMENT,
nome_categoria VARCHAR(100) NOT NULL
);

CREATE TABLE salas(
id_sala INT PRIMARY KEY AUTO_INCREMENT,
codigo_sala VARCHAR(10) NOT NULL,
status_sala ENUM("ESPERA", "EM JOGO", "FINALIZADA") NOT NULL,
data_criacao_sala DATETIME,
id_usuario INT NOT NULL,
id_categoria INT NOT NULL,
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);

CREATE TABLE participantes(
id_participante INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
pontuacao INT DEFAULT 0,
data_entrada DATETIME,
id_sala INT NOT NULL,
id_usuario INT NOT NULL,
FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
FOREIGN KEY (id_sala) REFERENCES salas(id_sala)
);

CREATE TABLE perguntas(
id_pergunta INT PRIMARY KEY AUTO_INCREMENT,
pergunta TEXT NOT NULL,
opcoes JSON,
resposta_certa VARCHAR(250)
);

CREATE TABLE respostas(
id_respostas INT PRIMARY KEY AUTO_INCREMENT,
resposta_dada VARCHAR(250),
correta BOOLEAN,
tempo_resposta_ms INT,
id_participante INT NOT NULL,
id_pergunta INT NOT NULL,
FOREIGN KEY (id_participante) REFERENCES participantes(id_participante),
FOREIGN KEY (id_pergunta) REFERENCES perguntas(id_pergunta)
);