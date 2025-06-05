-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 05 juin 2025 à 15:36
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
-- Base de données : `processus_participatif`
--

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

CREATE TABLE `demandes` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `numero_cin` varchar(50) NOT NULL,
  `projet` varchar(100) NOT NULL,
  `date_entrevue` date NOT NULL,
  `commentaire_entrevue` varchar(250) NOT NULL,
  `date_confirmation` date DEFAULT NULL,
  `commentaire_confirmation` text DEFAULT NULL,
  `type_contrat` varchar(50) DEFAULT NULL,
  `autre_contrat` varchar(100) DEFAULT NULL,
  `commentaire_contrat` text DEFAULT NULL,
  `commentaire_demande_acces` text DEFAULT NULL,
  `commentaire_reception_acces` text DEFAULT NULL,
  `commentaire_materiel` text DEFAULT NULL,
  `date_debut_travail` date DEFAULT NULL,
  `commentaire_debut` text DEFAULT NULL,
  `etat_avancement` enum('entrevue','confirmation_embauche','type_contrat','demande_acces','reception_acces','preparation_materiel','remise_materiel','debut_travail','complete') NOT NULL DEFAULT 'entrevue',
  `date_creation` datetime NOT NULL,
  `date_completion` datetime DEFAULT NULL,
  `commentaire_final` text DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lien_forms` varchar(255) DEFAULT NULL,
  `date_signature_contrat` date DEFAULT NULL,
  `date_remise_materiel` date DEFAULT NULL,
  `commentaire_remise_materiel` text DEFAULT NULL,
  `date_prevue_travail` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `nom`, `prenom`, `date_naissance`, `numero_cin`, `projet`, `date_entrevue`, `commentaire_entrevue`, `date_confirmation`, `commentaire_confirmation`, `type_contrat`, `autre_contrat`, `commentaire_contrat`, `commentaire_demande_acces`, `commentaire_reception_acces`, `commentaire_materiel`, `date_debut_travail`, `commentaire_debut`, `etat_avancement`, `date_creation`, `date_completion`, `commentaire_final`, `telephone`, `email`, `lien_forms`, `date_signature_contrat`, `date_remise_materiel`, `commentaire_remise_materiel`, `date_prevue_travail`) VALUES
(4, 'El Khalki', 'Naoufal ', '2025-05-01', 'G691415', 'IT', '2025-05-21', '', '2025-05-16', '', 'CDD', '', '', '', '', '', NULL, NULL, 'preparation_materiel', '2025-05-21 11:48:56', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'Noura', 'Aboutahir', '2025-06-19', 'GG22222', 'RH', '2025-06-06', '', '2025-06-05', '', 'CDD', '', '', '', NULL, NULL, NULL, NULL, 'demande_acces', '2025-06-05 11:47:06', NULL, NULL, '0646820421', 'nlkhalkd@ghd.dd', 'https://www.google.com/', NULL, NULL, NULL, '2025-08-01'),
(6, 'bbb', 'bbb', '2025-06-05', 'bvbv', 'rr', '2025-06-03', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'entrevue', '2025-06-05 11:52:16', NULL, NULL, '00222222', 'rrr@rrrrr.rr', 'https://www.google.com/', NULL, NULL, NULL, '2025-06-05');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$SQSiKsryZOzpcV3nlKsz1uBey0hPpaIXlNOWq9DtOng5l8X0R/.BS', '2025-05-21 11:54:21');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_etat_avancement` (`etat_avancement`),
  ADD KEY `idx_date_creation` (`date_creation`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `demandes`
--
ALTER TABLE `demandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
