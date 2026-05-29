# Gestion de stock informatique

Projet L3 SI & BD - Application web PHP MVC de gestion de stock.

## Prerequis

- Serveur web local : AMPPS, XAMPP, WAMP ou equivalent
- PHP >= 8.1
- MySQL >= 5.7 ou MariaDB
- Module Apache : mod_rewrite (active par defaut dans AMPPS/XAMPP)

## Installation

1. Copier le dossier `gestion-stock` dans le repertoire web de votre serveur.
   - AMPPS : `www/`
   - XAMPP : `htdocs/`
   - WAMP  : `www/`

2. Creer la base de donnees en executant le script SQL :
   - Ouvrir phpMyAdmin : http://localhost/phpmyadmin
   - Onglet "SQL", coller et executer le contenu de `database.sql`

3. Verifier la configuration de la connexion dans `config.php` :
   - Par defaut : hote `localhost`, utilisateur `root`, mot de passe vide
   - Modifier selon votre configuration si necessaire

4. Acceder a l application : http://localhost/gestion-stock/

## Comptes de test

| Email            | Role           | Mot de passe |
|------------------|----------------|--------------|
| admin@stock.fr   | administrateur | password     |
| gest@stock.fr    | gestionnaire   | password     |
| emp@stock.fr     | employe        | password     |

## Lancer les tests unitaires

Depuis la racine du projet, en ligne de commande :

```
php tests/tests.php
```

Les tests utilisent une connexion reelle a la base de donnees.
Ils creent puis suppriment leurs propres donnees sans alterer les donnees existantes.

## Structure du projet (MVC)

```
gestion-stock/
├── index.php               Point d entree unique (routeur)
├── config.php              Configuration base de donnees
├── database.sql            Script SQL (creation + donnees de test)
├── .htaccess               Securite Apache
├── controllers/            Logique metier et routage des actions
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── MaterielController.php
│   ├── UtilisateurController.php
│   ├── AffectationController.php
│   ├── CategorieController.php
│   └── PanneController.php
├── models/                 Acces aux donnees (PDO)
│   ├── Model.php
│   ├── Materiel.php
│   ├── Utilisateur.php
│   ├── Affectation.php
│   ├── Categorie.php
│   └── Panne.php
├── views/                  Templates HTML/PHP
│   ├── layout_top.php
│   ├── layout_bottom.php
│   ├── login.php
│   ├── dashboard.php
│   ├── materiels/
│   ├── utilisateurs/
│   ├── affectations/
│   ├── categories/
│   └── pannes/
├── public/
│   ├── style.css
│   └── script.js
└── tests/
    └── tests.php
```

## Roles et droits

| Fonctionnalite            | Administrateur | Gestionnaire | Employe |
|---------------------------|:--------------:|:------------:|:-------:|
| Tableau de bord           | Oui            | Oui          | Oui     |
| Voir les materiels        | Oui            | Oui          | Oui     |
| Ajouter / modifier materiel | Oui          | Oui          | Non     |
| Affecter / retour         | Oui            | Oui          | Non     |
| Declarer une panne        | Oui            | Oui          | Oui     |
| Gerer les pannes          | Oui            | Oui          | Non     |
| Gerer les utilisateurs    | Oui            | Non          | Non     |
| Gerer les categories      | Oui            | Non          | Non     |
