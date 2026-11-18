CREATE DATABASE IF NOT EXISTS quitanda_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quitanda_db;

CREATE TABLE IF NOT EXISTS produtos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  categoria ENUM('Frutas','Legumes','Verduras') NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  unidade VARCHAR(30) NOT NULL DEFAULT 'kg',
  estoque INT UNSIGNED NOT NULL DEFAULT 0,
  imagem_url VARCHAR(500) DEFAULT NULL
) ENGINE=InnoDB;

INSERT INTO produtos (nome, categoria, preco, unidade, estoque, imagem_url) VALUES
('Banana Nanica','Frutas',5.99,'kg',45,'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=800'),
('Maçã Fuji','Frutas',9.90,'kg',32,'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?w=800'),
('Tomate Italiano','Legumes',7.49,'kg',28,'https://images.unsplash.com/photo-1546094096-0df4bcaaa337?w=800'),
('Alface Crespa','Verduras',3.50,'unidade',20,'https://images.unsplash.com/photo-1622206151226-18ca2c9ab4a1?w=800'),
('Cenoura','Legumes',4.79,'kg',40,'https://images.unsplash.com/photo-1445282768818-728615cc910a?w=800'),
('Batata Inglesa','Legumes',6.29,'kg',55,'https://images.unsplash.com/photo-1518977676601-b53f82aba655?w=800'),
('Mamão Papaia','Frutas',8.90,'kg',18,'https://images.unsplash.com/photo-1517282009859-f000ec3b26fe?w=800'),
('Cebola Roxa','Legumes',7.99,'kg',25,'https://images.unsplash.com/photo-1508747703725-719777637510?w=800'),
('Morango','Frutas',12.90,'bandeja',15,'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=800'),
('Abacaxi Pérola','Frutas',7.50,'unidade',12,'https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=800'),
('Brócolis','Verduras',6.90,'maço',16,'https://images.unsplash.com/photo-1459411621453-7b03977f4bfc?w=800'),
('Laranja Pera','Frutas',4.99,'kg',60,'https://images.unsplash.com/photo-1547514701-42782101795e?w=800');
