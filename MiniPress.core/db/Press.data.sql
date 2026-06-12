-- Adminer 4.17.1 MySQL 11.7.2-MariaDB-ubu2404 dump
use minipress;
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- --------------------------------------------------------
-- Users
-- --------------------------------------------------------
INSERT INTO `user` (`id`, `user_id`, `password`, `role`) VALUES
('a1b2c3d4-e5f6-7890-abcd-ef1234567801', 'alice.martin',   '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 100),
('a1b2c3d4-e5f6-7890-abcd-ef1234567802', 'bob.dupont',     '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 10),
('a1b2c3d4-e5f6-7890-abcd-ef1234567803', 'claire.leblanc', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 10),
('a1b2c3d4-e5f6-7890-abcd-ef1234567804', 'david.moreau',   '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 10),
('a1b2c3d4-e5f6-7890-abcd-ef1234567805', 'emma.bernard',   '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 10),
('a1b2c3d4-e5f6-7890-abcd-ef1234567806', 'francois.petit', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 10),
('a1b2c3d4-e5f6-7890-abcd-ef1234567807', 'gabrielle.roy',  '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 1),
('a1b2c3d4-e5f6-7890-abcd-ef1234567808', 'hugo.thomas',    '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 1),
('a1b2c3d4-e5f6-7890-abcd-ef1234567809', 'isabelle.simon', '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 1),
('a1b2c3d4-e5f6-7890-abcd-ef1234567810', 'julien.garcia',  '$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW', 1);

-- --------------------------------------------------------
-- Categories
-- --------------------------------------------------------
INSERT INTO `categorie` (`libelle`, `description`) VALUES
('Politique',       'Actualités politiques nationales et internationales'),
('Sport',           'Résultats, analyses et suivis sportifs'),
('Technologie',     'Innovations, numérique et nouvelles technologies'),
('Culture',         'Arts, cinéma, musique et littérature'),
('Science',         'Découvertes scientifiques et recherche'),
('Économie',        'Marchés financiers, entreprises et emploi'),
('Santé',           'Médecine, bien-être et santé publique'),
('Environnement',   'Écologie, climat et développement durable'),
('Éducation',       'Système éducatif, formations et universités'),
('International',   'Actualités mondiales et géopolitique');

-- --------------------------------------------------------
-- Articles
-- --------------------------------------------------------
INSERT INTO `Article` (`id`, `titre`, `resumer`, `contenue`, `date`, `categorie`, `url_image`, `id_auteur`, `published`) VALUES
('art-00000000-0000-0000-0000-000000000001', 'Réforme constitutionnelle en débat',
 'Le gouvernement présente un projet de réforme constitutionnelle très attendu.',
 'Le projet de réforme constitutionnelle soumis à l''Assemblée nationale suscite de vifs débats. Les opposants craignent une concentration du pouvoir exécutif, tandis que les partisans y voient une modernisation nécessaire des institutions.',
 '2026-01-10 09:00:00', 1, 'politique_reforme.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567802', 1),

('art-00000000-0000-0000-0000-000000000002', 'Championnat de France : résultats du week-end',
 'Retour sur les résultats marquants de la 22e journée de Ligue 1.',
 'La 22e journée de Ligue 1 a livré son lot de surprises. Le leader a concédé un match nul à domicile, permettant à son dauphin de revenir à deux points au classement général.',
 '2026-01-12 18:30:00', 2, 'sport_ligue1.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567803', 1),

('art-00000000-0000-0000-0000-000000000003', 'L''IA générative transforme le monde du travail',
 'Une étude révèle l''impact croissant de l''intelligence artificielle sur les métiers.',
 'Selon une étude publiée par l''OCDE, près de 40 % des emplois dans les pays développés seraient susceptibles d''être transformés par l''intelligence artificielle d''ici 2030. Les secteurs les plus touchés sont la finance, le droit et le journalisme.',
 '2026-01-15 11:00:00', 3, 'tech_ia.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567804', 1),

('art-00000000-0000-0000-0000-000000000004', 'Festival de Cannes : la sélection officielle dévoilée',
 'La sélection officielle du Festival de Cannes 2026 a été annoncée ce mardi.',
 'Le comité de sélection du Festival de Cannes a dévoilé une liste de 21 films en compétition officielle, avec une représentation inédite du cinéma africain et asiatique. La Palme d''Or sera remise le 24 mai.',
 '2026-01-18 14:00:00', 4, 'culture_cannes.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567805', 1),

('art-00000000-0000-0000-0000-000000000005', 'Découverte d''un nouveau matériau supraconducteur',
 'Des chercheurs annoncent une avancée majeure dans la physique des matériaux.',
 'Une équipe internationale de physiciens a annoncé la synthèse d''un matériau présentant des propriétés supraconductrices à température ambiante. Cette découverte pourrait révolutionner le transport d''énergie et l''électronique.',
 '2026-01-20 10:00:00', 5, 'science_supra.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567806', 1),

('art-00000000-0000-0000-0000-000000000006', 'La BCE maintient ses taux directeurs',
 'La Banque centrale européenne confirme sa politique monétaire pour le premier trimestre.',
 'Lors de sa réunion mensuelle, le conseil des gouverneurs de la BCE a décidé de maintenir ses taux directeurs inchangés. La présidente a évoqué une inflation maîtrisée mais a appelé à la vigilance face aux tensions géopolitiques.',
 '2026-01-22 16:00:00', 6, 'eco_bce.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567802', 1),

('art-00000000-0000-0000-0000-000000000007', 'Nouveau vaccin contre la dengue approuvé en Europe',
 'L''EMA donne son feu vert à un vaccin quadrivalent contre la dengue.',
 'L''Agence européenne des médicaments a approuvé un nouveau vaccin quadrivalent contre la dengue, jugé efficace à 85 % selon les essais cliniques de phase III. La vaccination sera recommandée pour les voyageurs en zones tropicales.',
 '2026-01-25 08:30:00', 7, 'sante_vaccin.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567803', 1),

('art-00000000-0000-0000-0000-000000000008', 'COP31 : les engagements climatiques en question',
 'Bilan mitigé à l''issue de la 31e Conférence des parties sur le climat.',
 'La COP31 s''est conclue sur un accord jugé insuffisant par les ONG environnementales. Si les pays signataires se sont engagés à réduire leurs émissions de 45 % d''ici 2035, aucun mécanisme contraignant n''a été adopté.',
 '2026-01-28 12:00:00', 8, 'env_cop31.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567804', 0),

('art-00000000-0000-0000-0000-000000000009', 'Réforme du baccalauréat : ce qui change en 2027',
 'Le ministère de l''Éducation nationale annonce de nouvelles modalités pour l''examen.',
 'À partir de la session 2027, le baccalauréat général comportera une épreuve de grand oral renforcée et l''introduction d''une matière obligatoire d''éducation numérique. Les lycéens et enseignants expriment des avis partagés.',
 '2026-02-01 09:30:00', 9, 'educ_bac.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567805', 1),

('art-00000000-0000-0000-0000-000000000010', 'Tensions en mer de Chine méridionale',
 'De nouvelles manœuvres militaires relancent les tensions dans la région.',
 'La marine chinoise a conduit des exercices militaires de grande envergure en mer de Chine méridionale, à proximité des eaux revendiquées par les Philippines. Washington a réaffirmé ses engagements de défense envers Manille.',
 '2026-02-05 07:00:00', 10, 'intl_chine.jpg', 'a1b2c3d4-e5f6-7890-abcd-ef1234567806', 1);

