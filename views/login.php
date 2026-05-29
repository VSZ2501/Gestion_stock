<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion de stock</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body class="page-login">

<div class="login-box">
    <h1>Gestion de stock</h1>
    <h2>Connexion</h2>

    <?php if (!empty($erreur)): ?>
        <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?page=auth&action=connecter">
        <div class="champ">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus>
        </div>
        <div class="champ">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        </div>
        <button type="submit">Se connecter</button>
    </form>

    <p class="aide-login">
        Comptes de test (mot de passe : <code>password</code>) :<br>
        admin@stock.fr &mdash; gest@stock.fr &mdash; emp@stock.fr
    </p>
</div>

</body>
</html>
