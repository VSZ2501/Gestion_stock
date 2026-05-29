<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de stock</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <span class="site-title">Gestion de stock informatique</span>
        <nav>
            <?php
            $role = $_SESSION['utilisateur']['role'] ?? '';
            $user = $_SESSION['utilisateur'];
            ?>

            <a href="index.php?page=dashboard">Tableau de bord</a>
            <a href="index.php?page=materiels">Materiels</a>
            <a href="index.php?page=pannes">Pannes</a>

            <?php if (in_array($role, ['administrateur', 'gestionnaire'])): ?>
                <a href="index.php?page=affectations">Affectations</a>
            <?php endif; ?>

            <?php if ($role === 'administrateur'): ?>
                <a href="index.php?page=utilisateurs">Utilisateurs</a>
                <a href="index.php?page=categories">Categories</a>
            <?php endif; ?>

            <span class="nav-user">
                <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                (<?= htmlspecialchars($role) ?>)
                &mdash;
                <a href="index.php?page=auth&action=deconnecter">Deconnexion</a>
            </span>
        </nav>
    </div>
</header>

<main>
<?php
// La vue appelante insere son contenu ici
// Ce fichier est inclus via require dans chaque vue
