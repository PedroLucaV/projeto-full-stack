DROP DATABASE IF EXISTS forcatotal;

CREATE DATABASE forcatotal;

USE forcatotal;

CREATE TABLE equipamento (
    id int auto_increment primary key,
    nome VARCHAR(200) NOT NULL,
    marca VARCHAR(200) NOT NULL,
    modelo VARCHAR(200) NOT NULL,
    ano_de_fabricacao YEAR NOT NULL,
    numero_de_serie VARCHAR(250) NOT NULL,
    capacidade_maxima INT NOT NULL,
    localizacao VARCHAR(100) NOT NULL,
    estado ENUM(
        'novo',
        'bom',
        'gasto',
        'quebrado'
    ),
    CREATED_AT TIMESTAMP DEFAULT(NOW()),
    UPDATE_AT TIMESTAMP DEFAULT(NOW()) on update now()
);

CREATE TABLE membros (
    id int auto_increment primary key,
    nome VARCHAR(200) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    data_de_nascimento DATE NOT NULL,
    endereco VARCHAR(250) NOT NULL,
    telefone INT(11) NOT NULL UNIQUE,
    data_de_matricula DATE,
    plano_de_contrato ENUM('PLUS', 'PRO', 'OMEGA'),
    CREATED_AT TIMESTAMP DEFAULT(NOW()),
    UPDATE_AT TIMESTAMP DEFAULT(NOW()) on update now()
);

CREATE TABLE usuarios (
    id int auto_increment primary key,
    nome VARCHAR(200) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    telefone INT(11) NOT NULL UNIQUE,
    email VARCHAR(200) NOT NULL UNIQUE,
    senha_hash VARCHAR(250) NOT NULL,
    tipo ENUM('Membro', 'Admin'),
    id_membro INT,
    CREATED_AT TIMESTAMP DEFAULT(NOW()),
    UPDATE_AT TIMESTAMP DEFAULT(NOW()) on update now(),
    Foreign Key (id_membro) REFERENCES membros (id)
);

CREATE TABLE reservas (
    id int auto_increment primary key,
    id_usuario int not null,
    id_equipamento int not null,
    data_reserva DATETIME not null,
    data_termino DATETIME NOT NULL,
    CREATED_AT TIMESTAMP DEFAULT(NOW()),
    UPDATE_AT TIMESTAMP DEFAULT(NOW()) on update now(),
    Foreign Key (id_usuario) REFERENCES usuarios (id),
    Foreign Key (id_equipamento) REFERENCES equipamento (id)
);