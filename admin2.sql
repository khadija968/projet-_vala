-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 31 juil. 2025 à 15:18
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `admin2`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(4, 'admin', '$2y$10$YIclM/r37fN1u.MUln01EuhnfGmy6W2y4jWdPmuAoXkTYlYWUBjG6');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `projet_id` int(11) DEFAULT NULL,
  `sender` varchar(50) DEFAULT NULL,
  `contenu` text DEFAULT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `statut` varchar(20) DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `projet_id`, `sender`, `contenu`, `date_envoi`, `statut`) VALUES
(1, 3, 'admin', 'bonne', '2025-07-30 22:21:31', 'actif'),
(2, 4, 'admin', 'bonne', '2025-07-31 01:32:37', 'actif'),
(3, 5, 'admin', 'bonne', '2025-07-31 01:35:08', 'actif'),
(4, 6, 'admin', 'bonn', '2025-07-31 02:32:33', 'actif'),
(5, 7, 'admin', 'bonne', '2025-07-31 03:11:57', 'actif'),
(6, 9, 'admin', 'bonne', '2025-07-31 03:15:34', 'actif'),
(7, 10, 'admin', 'bonne', '2025-07-31 03:19:33', 'actif'),
(8, 12, 'admin', 'bonne', '2025-07-31 03:58:38', 'actif'),
(9, 16, 'admin', 'bonne', '2025-07-31 04:06:29', 'actif'),
(10, 18, 'admin', 'bonne', '2025-07-31 04:21:15', 'actif'),
(11, 17, 'admin', 'bonne', '2025-07-31 14:53:43', 'actif'),
(12, 17, 'admin', 'cc', '2025-07-31 14:56:18', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `id` int(11) NOT NULL,
  `titre` varchar(100) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin_prevue` date DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`id`, `titre`, `contact_email`, `description`, `date_debut`, `date_fin_prevue`, `statut`) VALUES
(1, 'café', 'fatima23@gmail.com', 'exemple', '2025-09-01', '2025-09-25', 'archivé'),
(2, 'café', 'fatima23@gmail.com', 'exemple', '2025-09-01', '2025-09-25', 'archivé'),
(3, 'café', 'fatima23@gmail.com', 'exemple', '2025-09-01', '2025-09-25', 'archivé'),
(4, 'cabinet', 'khadijabr@gmail.com', 'exemple', '2025-08-01', '2025-08-20', 'archivé'),
(5, 'réstaurant', 'amira@gmail.com', 'exemple', '2025-09-02', '2025-09-22', 'archivé'),
(6, 'cabinet', 'fatima23@gmail.com', 'exemple', '2025-08-01', '2025-08-20', 'archivé'),
(7, 'cabinet', 'fatima23@gmail.com', 'exemple', '2025-08-01', '2025-08-20', 'archivé'),
(8, 'cabinet', 'fatima23@gmail.com', 'exemple', '2025-08-01', '2025-08-20', 'archivé'),
(9, 'café', 'khadijabr@gmail.com', 'exemple', '2025-09-01', '2025-09-25', 'archivé'),
(10, 'café', 'khadijabr@gmail.com', 'exemple', '2025-09-01', '2025-09-25', 'archivé'),
(11, 'cabinet', 'fatima23@gmail.com', 'exemple', '2025-09-01', '2025-09-25', 'archivé'),
(12, 'café', 'khadijabr@gmail.com', 'exemple', '2025-08-01', '2025-08-25', 'archivé'),
(13, 'café', 'khadijabr@gmail.com', 'exmple', '2025-08-01', '2025-08-25', 'archivé'),
(14, 'café', 'khadijabr@gmail.com', 'exemple', '2025-08-01', '2025-08-25', 'archivé'),
(15, 'cabinet', 'fatima23@gmail.com', 'exemple', '2025-09-01', '2025-09-23', 'archivé'),
(16, 'café', 'khadijabr@gmail.com', 'exemple', '2025-08-01', '2025-08-20', 'archivé'),
(17, 'café', 'khadijabr@gmail.com', 'exemple', '2025-08-01', '2025-08-20', 'en cours'),
(18, 'cabinet', 'fatima23@gmail.com', 'exemple', '2025-08-02', '2025-08-23', 'archivé');

-- --------------------------------------------------------

--
-- Structure de la table `stagiaires`
--

CREATE TABLE `stagiaires` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `ecole` varchar(100) DEFAULT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  `domaine` varchar(100) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'actif',
  `date_archivage` datetime DEFAULT NULL,
  `archive_par` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stagiaires`
--

INSERT INTO `stagiaires` (`id`, `nom`, `ecole`, `niveau`, `domaine`, `date_debut`, `date_fin`, `statut`, `date_archivage`, `archive_par`) VALUES
(1, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-30 22:15:34', NULL),
(2, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-30 22:16:21', 1),
(3, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-30 23:19:56', 1),
(4, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-31 01:59:51', 1),
(5, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-31 02:26:36', 1),
(6, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-31 03:16:52', 1),
(7, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-31 03:20:58', 1),
(8, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-31 03:54:48', 1),
(9, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-31 03:59:49', 1),
(10, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-31 04:07:40', 1),
(11, 'khadija', 'EST', '1 année', 'Informatique', '2025-08-01', '2025-08-20', 'archivé', '2025-07-31 04:22:25', 1);

-- --------------------------------------------------------

--
-- Structure de la table `suggestions_stage`
--

CREATE TABLE `suggestions_stage` (
  `id` int(11) NOT NULL,
  `stagiaire_id` int(11) DEFAULT NULL,
  `suggestion` text DEFAULT NULL,
  `date_generation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `suivis`
--

CREATE TABLE `suivis` (
  `id` int(11) NOT NULL,
  `stagiaire_id` int(11) NOT NULL,
  `travail` text NOT NULL,
  `progression` varchar(50) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `date_suivi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `suivis`
--

INSERT INTO `suivis` (`id`, `stagiaire_id`, `travail`, `progression`, `commentaire`, `date_suivi`) VALUES
(1, 4, 'nn', '1', 'NNN<', '2025-07-31 01:43:58'),
(2, 4, 'nn', '1', 'NNN<', '2025-07-31 01:44:03'),
(3, 4, 'nn', '1', 'NNN<', '2025-07-31 01:44:11'),
(4, 5, 'exemple', '67', 'bonne', '2025-07-31 02:25:52'),
(5, 5, 'exp', '22', 'bonne', '2025-07-31 02:26:11'),
(6, 6, 'café', '100', 'bonne', '2025-07-31 03:16:40'),
(7, 7, 'café', '100', 'bonne', '2025-07-31 03:20:34'),
(8, 9, 'café', '100', 'bonne', '2025-07-31 03:59:41'),
(9, 10, 'café', '100', 'bonne', '2025-07-31 04:07:31'),
(10, 11, 'café', '100', 'bonne', '2025-07-31 04:22:16');

-- --------------------------------------------------------

--
-- Structure de la table `suivis_stagiaires`
--

CREATE TABLE `suivis_stagiaires` (
  `id` int(11) NOT NULL,
  `stagiaire_id` int(11) NOT NULL,
  `travail` text NOT NULL,
  `progression` int(11) DEFAULT NULL CHECK (`progression` between 0 and 100),
  `commentaire` text DEFAULT NULL,
  `date_suivi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` int(11) NOT NULL,
  `cible_type` varchar(50) DEFAULT NULL,
  `cible_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_envoi` datetime DEFAULT current_timestamp(),
  `admin_username` varchar(100) NOT NULL,
  `sujet` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `support_messages`
--

INSERT INTO `support_messages` (`id`, `cible_type`, `cible_id`, `message`, `date_envoi`, `admin_username`, `sujet`) VALUES
(1, 'projet', 3, 'exemple', '2025-07-30 23:00:21', 'admin_test', 'café'),
(2, 'projet', 8, 'vérifier les dates', '2025-07-31 03:21:35', 'admin', 'cabinet'),
(3, 'projet', 11, 'vérifier les dates', '2025-07-31 04:00:39', 'admin', 'cabinet'),
(4, 'projet', 2, ';;', '2025-07-31 04:01:21', 'admin', ',,'),
(5, 'projet', 13, 'ff', '2025-07-31 04:02:05', 'admin', 'café'),
(6, 'projet', 15, 'vérifier les dates', '2025-07-31 04:09:07', 'admin', 'cabinet'),
(7, 'projet', 17, 'vérifier les dates', '2025-07-31 04:23:04', 'admin', 'café');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projet_id` (`projet_id`);

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stagiaires`
--
ALTER TABLE `stagiaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `suggestions_stage`
--
ALTER TABLE `suggestions_stage`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `suivis`
--
ALTER TABLE `suivis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stagiaire_id` (`stagiaire_id`);

--
-- Index pour la table `suivis_stagiaires`
--
ALTER TABLE `suivis_stagiaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stagiaire_id` (`stagiaire_id`);

--
-- Index pour la table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `stagiaires`
--
ALTER TABLE `stagiaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `suggestions_stage`
--
ALTER TABLE `suggestions_stage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `suivis`
--
ALTER TABLE `suivis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `suivis_stagiaires`
--
ALTER TABLE `suivis_stagiaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`projet_id`) REFERENCES `projets` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `suivis`
--
ALTER TABLE `suivis`
  ADD CONSTRAINT `suivis_ibfk_1` FOREIGN KEY (`stagiaire_id`) REFERENCES `stagiaires` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `suivis_stagiaires`
--
ALTER TABLE `suivis_stagiaires`
  ADD CONSTRAINT `suivis_stagiaires_ibfk_1` FOREIGN KEY (`stagiaire_id`) REFERENCES `stagiaires` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
