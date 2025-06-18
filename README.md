# Book Haven

Book Haven est une librairie en ligne développée avec Symfony. Elle permet de consulter, filtrer, ajouter, modifier et supprimer des livres, auteurs, catégories et formats. Le projet propose également un système de panier et une interface d’administration.

## Fonctionnalités principales

- **Catalogue de livres** : affichage, recherche et filtrage par auteur, catégorie, format.
- **Gestion des auteurs, catégories et formats** : création, modification, suppression (interface admin).
- **Ajout d’images de couverture pour les livres**.
- **Système de panier** : ajout de livres au panier (utilisateur connecté).
- **Pages statiques** : À propos, Conditions Générales de Vente.
- **Authentification** : accès restreint à l’administration.

## Accueil et Liste

 ![Accueil](public/accueil.png)
 ![Liste des livres](public/liste.png)

## Dépendances principales

- PHP >= 8.1
- Symfony >= 6.0
- Doctrine ORM
- Twig
- Composer

## Installation

```bash
# Installer les dépendances PHP
composer install

# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Charger les fixtures (optionnel)
php bin/console doctrine:fixtures:load

# Lancer le serveur de développement
symfony server:start
```

## Accès à l’administration

- Rendez-vous sur `/admin` après connexion avec un compte ayant le rôle administrateur.

## Auteur

Laura Borni  
Projet réalisé dans le cadre du BUT MMI – Spécialité DWeb à l’IUT