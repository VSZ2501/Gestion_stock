<?php require __DIR__ . '/../layout_top.php'; ?>

<?php $estModification = !empty($utilisateur); ?>

<div class="entete-section">
    <h1><?= $estModification ? 'Modifier un utilisateur' : 'Ajouter un utilisateur' ?></h1>
    <a href="index.php?page=utilisateurs">Retour a la liste</a>
</div>

<?php if (!empty($erreur)): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="post" action="index.php?page=utilisateurs&action=<?= $estModification ? 'mettreAJour' : 'enregistrer' ?>">
    <?php if ($estModification): ?>
        <input type="hidden" name="id" value="<?= $utilisateur['id'] ?>">
    <?php endif; ?>

    <div class="champ">
        <label for="nom">Nom *</label>
        <input type="text" id="nom" name="nom" required
               value="<?= $estModification ? htmlspecialchars($utilisateur['nom']) : '' ?>">
    </div>

    <div class="champ">
        <label for="prenom">Prenom *</label>
        <input type="text" id="prenom" name="prenom" required
               value="<?= $estModification ? htmlspecialchars($utilisateur['prenom']) : '' ?>">
    </div>

    <div class="champ">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required
               value="<?= $estModification ? htmlspecialchars($utilisateur['email']) : '' ?>">
    </div>

    <?php if (!$estModification): ?>
    <div class="champ">
        <label for="mot_de_passe">Mot de passe *</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
    </div>
    <?php endif; ?>

    <div class="champ">
        <label for="id_role">Role *</label>
        <select id="id_role" name="id_role" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id'] ?>"
                    <?= ($estModification && $utilisateur['id_role'] == $r['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($r['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit"><?= $estModification ? 'Enregistrer les modifications' : 'Creer l utilisateur' ?></button>
</form>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
