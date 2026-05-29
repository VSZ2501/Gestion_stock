<?php require __DIR__ . '/../layout_top.php'; ?>

<div class="entete-section">
    <h1>Enregistrer un retour</h1>
    <a href="index.php?page=affectations">Retour a la liste</a>
</div>

<?php if (!empty($erreur)): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="post" action="index.php?page=affectations&action=enregistrerRetour">
    <input type="hidden" name="id" value="<?= (int)($_GET['id'] ?? 0) ?>">

    <div class="champ">
        <label for="date_retour">Date de retour</label>
        <input type="date" id="date_retour" name="date_retour" value="<?= date('Y-m-d') ?>" required>
    </div>

    <div class="champ">
        <label for="commentaire">Commentaire (etat du materiel au retour)</label>
        <textarea id="commentaire" name="commentaire" rows="3"></textarea>
    </div>

    <button type="submit">Confirmer le retour</button>
</form>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
