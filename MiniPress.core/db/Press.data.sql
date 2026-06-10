-- Adminer 4.17.1 MySQL 11.7.2-MariaDB-ubu2404 dump
use giftbox;
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `Article` (`id`, `Titre`, `resumer`, `Contenue`, `Date`, `Categorie`, `ID-Auteur`, `Etats`) VALUES
('Article-1',	'Le petit Loup',	'quos dolorem libero',	'Quisquam a eaque eum ipsa est est. Nemo eveniet dolorum nisi. Voluptatem dolores veritatis tempore unde recusandae. Numquam at qui odio voluptas inventore non vel.',	10/06/2026,	'Action',	'Arthur',	'Valide',	),


INSERT INTO `categorie` (`id`, `libelle`, `description`) VALUES
(1,	'Action',	'restaurant, en cas, sur le pouce, livré ... toutes les façons de manger.'),


SET NAMES utf8mb4;

INSERT INTO `user` (`id`, `user_id`, `password`, `role`) VALUES
('9c02505f-af68-4b51-a5b6-e52b1805eee1',	'aurore06@example.org',	'$2y$10$jMVU9TsmnErWYHh6I.DxWOZNiuEB7.9mLEPUlMvylrkfWNV53cZoS',	1),

