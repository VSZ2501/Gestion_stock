<?php require __DIR__ . '/../layout_top.php'; ?>

<div class="entete-section">
    <h1>Declarer une panne</h1>
    <a href="index.php?page=pannes">Retour a la liste</a>
</div>

<?php if (!empty($erreur)): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<form method="post" action="index.php?page=pannes&action=enregistrer">
    <div class="champ">
        <label for="id_materiel">Materiel concerne *</label>
        <select id="id_materiel" name="id_materiel" required>
            <option value="">-- Choisir un materiel --</option>
            <?php foreach ($materiels as $m): ?>
                <option value="<?= $m['id'] ?>">
                    <?= htmlspecialchars($m['nom'] . ' (' . $m['numero_serie'] . ') - ' . $m['etat']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="champ">
        <label for="description">Description du probleme *</label>
        <textarea id="description" name="description" rows="4" required></textarea>
    </div>

    <button type="submit">Declarer la panne</button>
</form>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
