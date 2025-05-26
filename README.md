# Application Modulaire de Gestion de Stages

## Description
Application modulaire développée en PHP vanilla pour la gestion des stages, candidatures et soutenances. L'application est construite avec une architecture modulaire permettant une séparation claire des fonctionnalités.

## Modules

### 1. Module d'Authentification (AuthModule)
- Gestion des utilisateurs (inscription, connexion, déconnexion)
- Gestion des profils utilisateurs
- Historique des stages
- Gestion des candidatures personnelles

### 2. Module de Gestion des Candidatures (GestionCandidatureModule)
- Soumission de candidatures
- Gestion des CV et lettres de motivation
- Suivi des stages effectifs
- Planification et gestion des soutenances
- Système de notification par email

### 3. Module de Proposition de Stage (PropositionStageModule)
- Création de propositions de stage
- Gestion du statut des propositions (en cours, accepté, rejeté)
- Interface de consultation des propositions
- Tableau de bord administratif

## Prérequis
- PHP 7.4 ou supérieur
- Serveur MySQL/MariaDB
- Composer (Gestionnaire de dépendances PHP)
- Extension PDO PHP
- Extension PHP Mailer

## Installation

1. Cloner le dépôt :
```bash
git clone [url-du-depot]
cd my-modular-app
```

## Dépendances
```json
{
  "require": {
    "psr/container": "^2.0",
    "ext-pdo": "*",
    "vlucas/phpdotenv": "^5.6",
    "phpmailer/phpmailer": "^6.10"
  }
}
```
## Installation des dépendances :
```bash
composer install
```
## Configuration
- Copier le fichier .env.example en .env :
```bash
cp .env.example .env    
```
- Modifier les variables d'environnement dans le fichier.env avec les informations de connexion à la base de données.

## Utilisation
- Lancer le serveur local :
```bash
php -S localhost:8000 -t public
```
- Ouvrir le navigateur et accéder à l'application : ```
- Ouvrir le navigateur et accéder à l'application : URL_ADDRESS:8000
- Utiliser l'interface utilisateur pour se connecter, voir les propositions de stages , candidater, et gérer ces candidatures.
- Utiliser l'interface des Structures partenaire pour consulter les différentes propositions de stage, créer une nouvelle proposition.
- Utiliser l'interface de la Cellule de Collecte et Redaction pourla gestion des propositions de stage.
- Utiliser l'interface de la Cellule de Gestion des Candidatures pour la gestion des candidatures, des stages effectifs, et des soutenances .
- Accéder à l'interface d'administration pour la gestion des identifiants des structures partenaires et des Cellules de l'entreprise.
``` 
```	
## Routes :

### Module d'Authentification (AuthModule)
Gestion des comptes :

- GET /login : Affiche le formulaire de connexion
- POST /login : Traite la connexion
- GET /register : Affiche le formulaire d'inscription
- POST /register : Traite l'inscription
- GET /logout : Déconnexion

Gestion du profil :

- GET /profile : Affiche le profil de l'utilisateur
- GET /EditAccount : Affiche le formulaire de modification du compte
- POST /EditAccount : Traite la modification du compte
- GET /candidacy : Affiche les candidatures de l'utilisateur
- GET /IntershipHistory : Affiche l'historique des stages de l'utilisateur

### Module de Gestion des Candidatures (GestionCandidatureModule)
Gestion des candidatures :

- GET /candidater : Affiche le formulaire de candidature
- POST /candidater : Soumet une candidature
- GET /candidatures : Liste des candidatures
- GET /candidatureCV : Récupère le CV d'une candidature
- GET /candidatureLM : Récupère la lettre de motivation d'une candidature
- GET /propositionByCand : Obtient la proposition liée à une candidature
- POST /deleteCandidature : Supprime une candidature
Gestion des stages :

- POST /createStageEffectif : Crée un stage effectif
- GET /Stages : Affiche la page des stages
- GET /redirectionStage : Page de redirection des stages
Gestion des soutenances :

- GET /planifierSoutenance : Formulaire de planification de soutenance
- GET /Soutenances : Liste des soutenances
API Soutenances :

- GET /api/soutenances : Récupère toutes les soutenances
- GET /api/soutenances/{id} : Récupère une soutenance spécifique
- PUT /api/soutenances/{id}/status : Met à jour le statut d'une soutenance
- POST /api/soutenances : Crée une nouvelle soutenance
- PUT /api/soutenances/{id} : Met à jour une soutenance
- DELETE /api/soutenances/{id} : Supprime une soutenance

### Module de Proposition de Stage (PropositionStageModule)
Gestion des propositions :

- GET /Accueil : Page d'accueil
- GET /proposition_stage : Liste des propositions de stage
- POST /proposition/create : Crée une nouvelle proposition
- POST /proposition/update : Met à jour une proposition
- POST /proposition/statuts : Change le statut d'une proposition
- POST /proposition/delete : Supprime une proposition
- GET /PropositionDelete : Affiche la page de suppression
- GET /propositionCreate : Affiche le formulaire de création
- GET /PropositionBoard : Tableau de bord des propositions
Autres fonctionnalités :

- GET /api/sendmail : Route de test pour l'envoi d'emails de notification

## Configurationo et Exécution des Teests
- Intalletion des dépendances de test:
```bash
composer install --dev
```
- Exécution des tests:
```bash
composer test
```
- Exécution des tests avec couverture de code:
```bash
composer test-coverage
```
- Visualisation des résultats :
 Les résultats des tests s'afficheront dans la console
 Le rapport de couverture sera généré dans le dossier tests/coverage


## Conventions de Codage
- Suivre les standards PSR-4 pour l'autoloading
- Utiliser les standards PSR-12 pour le style de code
- Documenter les nouvelles fonctionnalités
- Ajouter des tests unitaires pour les nouvelles fonctionnalités