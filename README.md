# Système de Suivi des Processus de Recrutement (ProcessTrack)

Un système complet de gestion du processus d'embauche et d'intégration des candidats, développé en PHP et MySQL pour les environnements d'hébergement partagé.

## Fonctionnalités

- **Gestion multi-étapes des processus de recrutement**
- **Formulaire d'enregistrement des candidats**
- **Suivi détaillé de l'avancement des dossiers**
- **Système de commentaires à chaque étape**
- **Interface utilisateur moderne et réactive**
- **Tableau de bord avec filtres de recherche avancés**

## Installation

1. **Configuration de la base de données**
   
   Exécutez le script SQL `db_setup.sql` pour créer la base de données et les tables nécessaires.

2. **Configuration de la connexion**

   Modifiez le fichier `includes/db_connect.php` avec vos paramètres de connexion à la base de données.

   ```php
   $db_host = 'votre_serveur';
   $db_user = 'votre_utilisateur';
   $db_pass = 'votre_mot_de_passe';
   $db_name = 'processtrack';
   ```

3. **Transfert des fichiers**

   Transférez tous les fichiers sur votre hébergement partagé.

## Structure du processus

Le système suit les étapes suivantes pour chaque candidat:

1. **Entrevue initiale**
2. **Confirmation d'embauche**
3. **Définition du type de contrat**
4. **Demande d'accès**
5. **Réception des accès**
6. **Préparation du matériel**
7. **Début du travail**
8. **Processus complété**

## Sécurité

- Toutes les entrées utilisateur sont validées et échappées
- Protection contre les attaques XSS et les injections SQL
- Sessions sécurisées

## Maintien et développement

Pour ajouter de nouvelles fonctionnalités ou étapes au processus:

1. Modifiez la fonction `getNextStates()` dans `includes/functions.php`
2. Ajoutez les nouveaux champs dans la table `demandes`
3. Mettez à jour l'interface utilisateur dans le fichier `modifier-demande.php`

## Personnalisation

Vous pouvez personnaliser l'apparence en modifiant le fichier `assets/css/style.css`.