<?php require __DIR__ . '/../layout_top.php'; ?>

<?php $estModification = !empty($materiel); ?>

<div class="entete-section">
    <h1><?= $estModification ? 'Modifier un materiel' : 'Ajouter un materiel' ?></h1>
    <a href="index.php?page=materiels">Retour a la liste</a>
</div>

<?php if (!empty($erreur)): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="post" action="index.php?page=materiels&action=<?= $estModification ? 'mettreAJour' : 'enregistrer' ?>">
    <?php if ($estModification): ?>
        <input type="hidden" name="id" value="<?= $materiel['id'] ?>">
    <?php endif; ?>

    <?php if (!$estModification): ?>
    <div class="champ">
        <label for="numero_serie">Numero de serie *</label>
        <input type="text" id="numero_serie" name="numero_serie" required>
    </div>
    <?php endif; ?>

    <div class="champ">
        <label for="nom">Nom *</label>
        <input type="text" id="nom" name="nom" required
               value="<?= $estModification ? htmlspecialchars($materiel['nom']) : '' ?>">
    </div>

    <div class="champ">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="3"><?= $estModification ? htmlspecialchars($materiel['description']) : '' ?></textarea>
    </div>

    <div class="champ">
        <label for="id_categorie">Categorie *</label>
        <select id="id_categorie" name="id_categorie" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                    <?= ($estModification && $materiel['id_categorie'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="champ">
        <label for="quantite">Quantite</label>
        <input type="number" id="quantite" name="quantite" min="1" value="<?= $estModification ? $materiel['quantite'] : 1 ?>">
    </div>

    <?php if ($estModification): ?>
    <div class="champ">
        <label for="etat">Etat</label>
        <select id="etat" name="etat">
            <?php foreach (['disponible', 'affecte', 'panne', 'hors_service'] as $e): ?>
                <option value="<?= $e ?>" <?= $materiel['etat'] === $e ? 'selected' : '' ?>><?= $e ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php else: ?>
    <div class="champ">
        <label for="date_entree">Date d entree</label>
        <input type="date" id="date_entree" name="date_entree" value="<?= date('Y-m-d') ?>">
    </div>
    <?php endif; ?>

    <button type="submit"><?= $estModification ? 'Enregistrer les modifications' : 'Ajouter le materiel' ?></button>
</form>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
