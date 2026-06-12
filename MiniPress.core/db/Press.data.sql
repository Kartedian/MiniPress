-- Adminer 4.17.1 MySQL 11.7.2-MariaDB-ubu2404 dump
use minipress;
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `Article` (`id`, `Titre`, `resumer`, `Contenue`, `Date`, `Categorie`, `url_image`,`ID_Auteur`, `published`) VALUES
('Article-1',	'Le petit Loup',	'quos dolorem libero',	'Quisquam a eaque eum ipsa est est. Nemo eveniet dolorum nisi. Voluptatem dolores veritatis tempore unde recusandae. Numquam at qui odio voluptas inventore non vel.',	'2026-06-10', 1, 'url_image.jpg', 'Arthur',	1);


INSERT INTO `categorie` (`id`, `libelle`, `description`) VALUES
(1, 'Actualités', 'Informations récentes et événements marquants.'),
(2, 'Technologie', 'Articles sur les innovations, logiciels et matériel informatique.'),
(3, 'Développement Web', 'Tutoriels et actualités liés au développement web.'),
(4, 'Programmation', 'Conseils, bonnes pratiques et langages de programmation.'),
(5, 'Intelligence Artificielle', 'Articles sur l’IA, le machine learning et les technologies associées.'),
(6, 'Cybersécurité', 'Sécurité informatique, protection des données et bonnes pratiques.');


SET NAMES utf8mb4;

INSERT INTO `user` (`id`, `user_id`, `password`, `role`) VALUES
('9c02505f-af68-4b51-a5b6-e52b1805eee1',	'aurore06@example.org',	'$2y$10$jMVU9TsmnErWYHh6I.DxWOZNiuEB7.9mLEPUlMvylrkfWNV53cZoS',	1);

