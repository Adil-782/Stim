-- ========================================================
-- PROJET : STIM (Vapor Clone)
-- VERSION : 3.1 (Rebranded)
-- ========================================================

-- 1. Nettoyage et Création de la base
DROP DATABASE IF EXISTS stim_db;
CREATE DATABASE stim_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stim_db;

-- ========================================================
-- TABLE : USERS
-- ========================================================
CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(50) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL, -- Stocké en MD5 (CWE-328)
                       email VARCHAR(100),
                       role ENUM('user', 'admin', 'moderator') DEFAULT 'user',
                       wallet_balance DECIMAL(10, 2) DEFAULT 0.00,
                       avatar VARCHAR(255) DEFAULT 'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix',
                       is_public BOOLEAN DEFAULT TRUE -- Cible pour l'IDOR
);

-- ========================================================
-- TABLE : GAMES
-- ========================================================
CREATE TABLE games (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       title VARCHAR(100) NOT NULL,
                       description TEXT,
                       price DECIMAL(10, 2) NOT NULL,
                       image_cover VARCHAR(255), -- URL ou chemin local
                       release_date DATE
);

-- ========================================================
-- TABLE : REVIEWS
-- Mise à jour : Ajout de la colonne is_recommended
-- ========================================================
CREATE TABLE reviews (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         game_id INT,
                         user_id INT,
                         content TEXT, -- Faille XSS Stored ici
                         is_recommended BOOLEAN DEFAULT TRUE, -- 1 = Pouce Bleu, 0 = Pouce Rouge
                         posted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (game_id) REFERENCES games(id),
                         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ========================================================
-- TABLE : LIBRARY
-- ========================================================
CREATE TABLE library (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         user_id INT,
                         game_id INT,
                         purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (user_id) REFERENCES users(id),
                         FOREIGN KEY (game_id) REFERENCES games(id)
);

-- ========================================================
-- TABLE : HIDDEN_KEYS (Pour Injection SQL)
-- ========================================================
CREATE TABLE hidden_keys (
                             id INT AUTO_INCREMENT PRIMARY KEY,
                             key_code VARCHAR(100),
                             service_name VARCHAR(100),
                             dummy_price DECIMAL(10,2),
                             secret_flag VARCHAR(255)   -- LE FLAG EST ICI
);

-- ========================================================
-- TABLE : PASSWORD_RESET_TOKENS (Challenge 1 - Host Header Injection)
-- ========================================================
CREATE TABLE password_reset_tokens (
                                       id INT AUTO_INCREMENT PRIMARY KEY,
                                       user_id INT NOT NULL,
                                       token VARCHAR(64) NOT NULL UNIQUE,
                                       created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                                       expires_at DATETIME,
                                       used BOOLEAN DEFAULT FALSE,
                                       FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ========================================================
-- TABLE : GAME_KEYS (Challenge 2 - SQL Injection + IDOR)
-- ========================================================
CREATE TABLE game_keys (
                           id INT AUTO_INCREMENT PRIMARY KEY,
                           user_id INT NOT NULL,
                           game_id INT NOT NULL,
                           key_code VARCHAR(50) NOT NULL,
                           flag VARCHAR(255),
                           created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                           FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                           FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
);

INSERT INTO users (username, password, email, role, wallet_balance, is_public, avatar) VALUES
('admin', MD5('admin123'), 'admin@stim.local', 'admin', 999999.99, 1, 'https://api.dicebear.com/7.x/avataaars/svg?seed=Admin');

-- Vérification de la création
SELECT id, username, email, role, wallet_balance FROM users WHERE role = 'admin';

-- ========================================================
-- INSERTION DES DONNÉES (FIXTURES)
-- ========================================================

-- 1. Utilisateurs (Avec Avatars fixés)
INSERT INTO users (username, password, email, role, wallet_balance, is_public, avatar) VALUES
                                                                                           ('GabeN', MD5('admin_super_secure'), 'gaben@stim.corp', 'admin', 999999.99, 1, 'https://api.dicebear.com/7.x/avataaars/svg?seed=Gabe'),
                                                                                           ('DarkSasuke', MD5('password123'), 'sasuke@konoha.fr', 'user', 15.50, 1, 'https://api.dicebear.com/7.x/avataaars/svg?seed=Sasuke'),
                                                                                           ('NoobSaibot', MD5('toasty'), 'noob@mk.net', 'user', 0.00, 0, 'https://api.dicebear.com/7.x/avataaars/svg?seed=Noob'),
                                                                                           ('HackerMan', MD5('mrrobot'), 'elliot@fsociety.dat', 'user', 50.00, 1, 'https://api.dicebear.com/7.x/avataaars/svg?seed=Hacker');

-- 2. Jeux (Updated with Fixes)
INSERT INTO games (title, description, price, image_cover, release_date) VALUES
                                                                             ('Half-Life 3', 'Le mythe devenu réalité. Prenez votre pied de biche et préparez-vous.', 59.99, 'https://upload.wikimedia.org/wikipedia/en/2/25/Half-Life_2_cover.jpg', '2025-04-01'),
                                                                             ('Grand Theft Auto VI', 'Retournez à Vice City. Braquages, néons et trahisons en 8K.', 69.99, 'https://upload.wikimedia.org/wikipedia/en/a/a5/Grand_Theft_Auto_V.png', '2025-12-25'),
                                                                             ('Cyberpunk 2078', 'Le futur est sombre, mais vos implants brillent. (Garantie sans bugs)', 29.99, 'https://upload.wikimedia.org/wikipedia/en/9/9f/Cyberpunk_2077_box_art.jpg', '2024-01-10'),
                                                                             ('Elden Ring: Easy Mode', 'Enfin accessible aux journalistes de jeux vidéo. Appuyez sur X pour gagner.', 49.99, 'https://upload.wikimedia.org/wikipedia/en/b/b9/Elden_Ring_Box_art.jpg', '2023-05-15'),
                                                                             ('Goat Simulator: Space', 'Personne ne vous entendra bêler dans l\'espace.', 15.00, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/265930/header.jpg', '2024-08-20'),
                                                                             ('The Witcher 3: Wild Hunt', 'Incarnez Geralt de Riv, un chasseur de monstres, dans un monde ouvert dévasté par la guerre.', 29.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/header.jpg', '2015-05-19'),
                                                                             ('Red Dead Redemption 2', 'Arthur Morgan et la bande de Van der Linde tentent de survivre dans un Ouest sauvage en déclin.', 59.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1174180/header.jpg', '2019-12-05'),
                                                                             ('God of War', 'Kratos est de retour, cette fois dans le monde de la mythologie nordique, accompagné de son fils Atreus.', 49.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1593500/header.jpg', '2022-01-14'),
                                                                             ('Hades', 'Défiez le dieu des morts en vous frayant un chemin hors des Enfers dans ce rogue-like dungeon crawler.', 24.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1145360/header.jpg', '2020-09-17'),
                                                                             ('Among Us', 'Un jeu de travail d''équipe et de trahison... dans l''espace !', 3.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/945360/header.jpg', '2018-06-15'),
                                                                             ('Stardew Valley', 'Vous avez hérité de la vieille ferme de votre grand-père. Armé d''outils usagés et de quelques pièces, commencez une nouvelle vie.', 13.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/413150/header.jpg', '2016-02-26'),
                                                                             ('Terraria', 'Creusez, combattez, explorez, construisez ! Rien n''est impossible dans ce jeu d''aventure bourré d''action.', 9.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/105600/header.jpg', '2011-05-16'),
                                                                             ('Portal 2', 'Le ''Perpetual Testing Initiative'' a été étendu pour vous permettre de concevoir des niveaux coopératifs originaux.', 9.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/620/header.jpg', '2011-04-18'),
                                                                             ('Left 4 Dead 2', 'Un FPS coopératif d''action horrifique qui vous emmène, vous et vos amis, dans les villes, les marais et les cimetières du sud des États-Unis.', 9.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/550/header.jpg', '2009-11-17'),
                                                                             ('Hollow Knight', 'Forge your own path in Hollow Knight! An epic action adventure through a vast ruined kingdom of insects and heroes.', 14.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/367520/header.jpg', '2017-02-24'),
                                                                             ('Celeste', 'Aidez Madeline à survivre à ses démons intérieurs au mont Celeste, dans ce jeu de plateforme ultra-précis.', 19.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/504230/header.jpg', '2018-01-25'),
                                                                             ('Factorio', 'Factorio est un jeu dans lequel vous construisez et entretenez des usines.', 32.00, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/427520/header.jpg', '2020-08-14'),
                                                                             ('Rust', 'Le seul but dans Rust est de survivre. Tout veut votre mort.', 39.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/252490/header.jpg', '2018-02-08'),
                                                                             ('Valheim', 'Un jeu brutal d''exploration et de survie pour 1 à 10 joueurs, qui prend place dans un purgatoire en génération procédurale inspiré par la culture viking.', 19.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/892970/header.jpg', '2021-02-02'),
                                                                             ('Baldur''s Gate 3', 'Rassemblez votre groupe et retournez aux Royaumes Oubliés dans une histoire d''amitié, de trahison, de sacrifice et de survie.', 59.99, 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/1086940/header.jpg', '2023-08-03');

-- 3. Commentaires (Mise à jour avec Recommandation Oui/Non)
INSERT INTO reviews (game_id, user_id, content, is_recommended) VALUES
(1, 2, 'Incroyable, je ne pensais pas voir ça de mon vivant !', 1),
(3, 3, 'Encore quelques glitchs, mais jouable avec une RTX 5090.', 1),
(1, 1, 'Worth the weight.', 1),
(4, 2, 'Trop facile, aucun challenge. Remboursez !', 0);

-- 4. Bibliothèque (Pour tester l''IDOR)
INSERT INTO library (user_id, game_id) VALUES (2, 1);
INSERT INTO library (user_id, game_id) VALUES (3, 4);

-- 5. Flags (Injection SQL)
INSERT INTO hidden_keys (key_code, service_name, dummy_price, secret_flag) VALUES
('XJ9-ABCD-1234', 'System Admin Access', 0.00, 'FLAG{SQL_INJECTION_MASTER_STIM}'),
('FREE-GAME-KEY', 'Half-Life 3 Dev Build', 0.00, 'Pas de flag ici, cherchez encore.');

-- 6. Game Keys (Challenge 2 - SQL Injection + IDOR)
INSERT INTO game_keys (user_id, game_id, key_code, flag) VALUES
(1, 1, 'HL3-GABE-NEWELL-2025', NULL),
(2, 1, 'HL3-DARK-SASUKE-KEY', NULL),
(2, 3, 'CP77-NIGHT-CITY-2078', NULL),
(3, 4, 'ELDR-EASY-MODE-NOOB', NULL),
(4, 5, 'GOAT-SPACE-HACKER-X', 'FLAG{IDOR_AND_SQLi_COMBO_BREAKER}');

-- 7. Admin user for Challenge 3 (XSS Cookie Theft)
INSERT INTO users (username, password, email, role, wallet_balance, is_public, avatar) VALUES
('admin_bot', MD5('super_secret_admin_2025'), 'admin@stim.corp', 'admin', 99999.99, 0, 'https://api.dicebear.com/7.x/avataaars/svg?seed=Admin');
