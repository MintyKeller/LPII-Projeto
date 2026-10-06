CREATE database palco; 
use palco; 

CREATE TABLE categoria (
	id_categ INT auto_increment primary KEY, 
    nome_show VARCHAR(100)
);

CREATE table shows (
	id_show INT auto_increment primary KEY, 
    categ_id int NOT NULL, 
    titulo VARCHAR(100) NOT NULL, 
    descricao VARCHAR(100)NOT NULL, 
    data_show DATE NOT NULL, 
    locals TEXT NOT NULL, 
    preco FLOAT NOT NULL, 
    capacidade int NOT NULL, 
    ing_disponiveis int NOT NULL, 
    imagem TEXT NOT NULL, 
    
    FOREIGN KEY (categ_id) REFERENCES categoria(id_categ)
);

CREATE TABLE vendas (
	id INT auto_increment primary KEY, 
    show_id int NOT NULL, 
    nome_comprador VARCHAR(100), 
    email VARCHAR(100), 
    cpf VARCHAR(15), 
    qtd_ing INT, 
    valor_total FLOAT, 
    data_compra DATE, 
    
      FOREIGN KEY (show_id) REFERENCES shows(id_show)
);

CREATE TABLE usuarios ( 
	id_usuario INT auto_increment primary KEY, 
    nome_usuario VARCHAR(100), 
    email_usuario VARCHAR(100), 
    senha VARCHAR(100)
);