<?php require __DIR__ . '/../layout_top.php'; ?>

<div class="entete-section">
    <h1>Categories</h1>
</div>

<?php if ($succes): ?>
    <p class="message succes">Operation effectuee avec succes.</p>
<?php endif; ?>

<?php if (!empty($erreur)): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<section class="deux-colonnes">
    <div>
        <h2>Liste des categories</h2>
        <?php if (empty($liste)): ?>
            <p>Aucune categorie.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr><th>Libelle</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($liste as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['libelle']) ?></td>
                    <td>
                        <a href="#" onclick="remplirForm(<?= $c['id'] ?>, '<?= htmlspecialchars($c['libelle'], ENT_QUOTES) ?>')">Modifier</a>
                        &mdash;
                        <a href="index.php?page=categories&action=supprimer&id=<?= $c['id'] ?>"
                           onclick="return confirm('Supprimer cette categorie ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <div>
        <h2 id="form-titre">Ajouter une categorie</h2>
        <form method="post" action="index.php?page=categories&action=enregistrer">
            <input type="hidden" name="id" id="form-id" value="0">
            <div class="champ">
                <label for="libelle">Libelle *</label>
                <input type="text" id="libelle" name="libelle" required>
            </div>
            <button type="submit">Enregistrer</button>
            <a href="index.php?page=categories" id="btn-annuler" style="display:none">Annuler</a>
        </form>
    </div>
</section>

<script>
function remplirForm(id, libelle) {
    document.getElementById('form-id').value = id;
    document.getElementById('libelle').value = libelle;
    document.getElementById('form-titre').textContent = 'Modifier la categorie';
    document.getElementById('btn-annuler').style.display = 'inline';
    document.getElementById('libelle').focus();
}
</script>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
