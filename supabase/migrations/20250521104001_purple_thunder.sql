-- Base de données: `processtrack`
CREATE DATABASE IF NOT EXISTS `processtrack` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `processtrack`;

-- Structure de la table `demandes`
CREATE TABLE IF NOT EXISTS `demandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `numero_cin` varchar(50) NOT NULL,
  `projet` varchar(100) NOT NULL,
  `date_entrevue` date NOT NULL,
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
  `etat_avancement` enum('entrevue','confirmation_embauche','type_contrat','demande_acces','reception_acces','preparation_materiel','debut_travail','complete') NOT NULL DEFAULT 'entrevue',
  `date_creation` datetime NOT NULL,
  `date_completion` datetime DEFAULT NULL,
  `commentaire_final` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_etat_avancement` (`etat_avancement`),
  KEY `idx_date_creation` (`date_creation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de test
INSERT INTO `demandes` (`nom`, `prenom`, `date_naissance`, `numero_cin`, `projet`, `date_entrevue`, `date_confirmation`, `type_contrat`, `etat_avancement`, `date_creation`) VALUES
('Dupont', 'Jean', '1985-06-15', 'AB123456', 'Projet Alpha', '2023-03-10', '2023-03-15', 'CDI', 'type_contrat', NOW()),
('Martin', 'Sophie', '1990-09-22', 'CD789012', 'Projet Beta', '2023-03-12', NULL, NULL, 'entrevue', NOW()),
('Dubois', 'Michel', '1982-11-30', 'EF345678', 'Projet Gamma', '2023-03-05', '2023-03-08', 'CDD', 'preparation_materiel', NOW());