<?php require __DIR__ . '/../layout_top.php'; ?>

<div class="entete-section">
    <h1>Nouvelle affectation</h1>
    <a href="index.php?page=affectations">Retour a la liste</a>
</div>

<?php if (!empty($erreur)): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<?php if (empty($materiels)): ?>
    <p class="message">Aucun materiel disponible a affecter.</p>
<?php else: ?>

<form method="post" action="index.php?page=affectations&action=enregistrer">
    <div class="champ">
        <label for="id_materiel">Materiel *</label>
        <select id="id_materiel" name="id_materiel" required>
            <option value="">-- Choisir un materiel disponible --</option>
            <?php foreach ($materiels as $m): ?>
                <option value="<?= $m['id'] ?>">
                    <?= htmlspecialchars($m['nom'] . ' (' . $m['numero_serie'] . ')') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="champ">
        <label for="id_utilisateur">Employe *</label>
        <select id="id_utilisateur" name="id_utilisateur" required>
            <option value="">-- Choisir un employe --</option>
            <?php foreach ($employes as $u): ?>
                <option value="<?= $u['id'] ?>">
                    <?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="champ">
        <label for="date_affectation">Date d affectation</label>
        <input type="date" id="date_affectation" name="date_affectation" value="<?= date('Y-m-d') ?>">
    </div>

    <div class="champ">
        <label for="commentaire">Commentaire</label>
        <textarea id="commentaire" name="commentaire" rows="3"></textarea>
    </div>

    <button type="submit">Enregistrer l affectation</button>
</form>

<?php endif; ?>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
