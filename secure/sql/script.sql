-- SQLBook: Code
CREATE DATABASE dbNovoEstilo;

-- Tabela Cliente
CREATE TABLE dbNovoEstilo.Cliente (
    id_cli INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    data_cadastro_cli DATETIME,
    nome_cli VARCHAR(100) NOT NULL,
    nome_social VARCHAR(100),
    email_cli VARCHAR(100) NOT NULL,
    telefone_cli VARCHAR(20),
    celular_cli VARCHAR(20),
    data_nascimento DATE,
    tipo_do_documento_cli VARCHAR(50) NOT NULL,
    documento_cli VARCHAR(50) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    rua VARCHAR(100) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    complemento VARCHAR(100),
    cep VARCHAR(10),
    status_cli VARCHAR(20) NOT NULL
);

-- Tabela Usuario
CREATE TABLE dbNovoEstilo.Usuario (
    id_usu INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    data_cadastro_usu DATETIME,
    nome_usu VARCHAR(100) NOT NULL,
    nome_social VARCHAR(100),
    email_usu VARCHAR(100) NOT NULL,
    telefone_usu VARCHAR(20),
    celular_usu VARCHAR(20),
    data_nascimento DATE,
    tipo_do_documento_usu VARCHAR(50) NOT NULL,
    documento_usu VARCHAR(50) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    rua VARCHAR(100) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    complemento VARCHAR(100),
    cep VARCHAR(10),
    status_usu VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL
);

-- Tabela Fornecedor
CREATE TABLE dbNovoEstilo.Fornecedor (
    id_for INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    data_cadastro_for DATETIME,
    nome_for VARCHAR(100) NOT NULL,
    email_for VARCHAR(100) NOT NULL,
    telefone_for VARCHAR(20),
    celular_for VARCHAR(20),
    tipo_do_documento_for VARCHAR(50) NOT NULL,
    documento_for VARCHAR(50) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100) NOT NULL,
    rua VARCHAR(100 NOT NULL),
    cep VARCHAR(10) NOT NULL,
    status_for VARCHAR(20) NOT NULL
);

-- Tabela Produto
CREATE TABLE dbNovoEstilo.Produto (
    id_prod INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_prod VARCHAR(100) NOT NULL,
    desc_prod VARCHAR(255),
    marca VARCHAR(100) NOT NULL,
    preco_venda DECIMAL(10,2) NOT NULL,
    estoque_minimo INT NOT NULL,
    status_prod VARCHAR(20) NOT NULL
);

-- Tabela Servico
CREATE TABLE dbNovoEstilo.Servico (
    id_serv INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_serv VARCHAR(100) NOT NULL,
    desc_serv VARCHAR(255),
    prazo_serv DATETIME NOT NULL,
    preco_serv DECIMAL(10,2),
    status_serv VARCHAR(20) NOT NULL
);

-- Tabela items_os
CREATE TABLE dbNovoEstilo.Items_os (
    id_ordem INT,
    id_serv INT,
    preco_items_os DECIMAL(10,2) NOT NULL
    -- FOREIGN KEY (id_serv) REFERENCES Servico(id_serv)
    -- FOREIGN KEY (id_serv) REFERENCES Servico(id_serv)
);

-- Tabela ordem_servico
CREATE TABLE dbNovoEstilo.Ordem_servico (
    id_ordem INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_cli INT,
    id_usu INT,
    data_ordem_servico DATETIME NOT NULL
    -- FOREIGN KEY (id_cli) REFERENCES Cliente(id_cli),
    -- FOREIGN KEY (id_usu) REFERENCES Usuario(id_usu)
);

-- Tabela compra
CREATE TABLE dbNovoEstilo.Compra (
    id_compra INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    data_compra DATETIME NOT NULL,
    id_for INT,
    id_usu INT,
    prev_entrega DATETIME NOT NULL,
    data_entrega_efetiva DATETIME NOT NULL,
    preco_compra DECIMAL(10,2) NOT NULL
    -- FOREIGN KEY (id_for) REFERENCES Fornecedor(id_for),
    -- FOREIGN KEY (id_usu) REFERENCES Usuario(id_usu)
);

-- Tabela items_compra
CREATE TABLE dbNovoEstilo.Items_compra (
    id_compra INT,
    id_prod INT,
    preco_items_compra DECIMAL(10,2) NOT NULL
    -- FOREIGN KEY (id_compra) REFERENCES compra(id_compra),
    -- FOREIGN KEY (id_prod) REFERENCES Produto(id_prod)
);

-- Tabela pedido
CREATE TABLE dbNovoEstilo.Pedido (
    id_ped INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    data_ped DATETIME NOT NULL,
    id_cli INT,
    id_usu INT,
    endereco_entrega VARCHAR(255) NOT NULL,
    data_entrega_ped DATETIME NOT NULL
    -- FOREIGN KEY (id_cli) REFERENCES Cliente(id_cli),
    -- FOREIGN KEY (id_usu) REFERENCES Usuario(id_usu)
);

-- Tabela items_pedido
CREATE TABLE dbNovoEstilo.items_pedido (
    id_ped INT,
    id_prod INT,
    preco_vendido DECIMAL(10,2) NOT NULL
    -- FOREIGN KEY (id_ped) REFERENCES pedidos(id_ped),
    -- FOREIGN KEY (id_prod) REFERENCES Produto(id_prod)
);

select * from Produto;

select * from Usuario;

select * from Fornecedor;

SELECT * from Servico;

select * from Cliente;

select * from Ordem_servico;

SELECT * FROM Items_os;

select * from Pedido;
