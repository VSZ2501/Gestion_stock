<?php require __DIR__ . '/../layout_top.php'; ?>

<div class="entete-section">
    <h1>Materiels</h1>
    <?php if (in_array($_SESSION['utilisateur']['role'], ['administrateur', 'gestionnaire'])): ?>
        <a href="index.php?page=materiels&action=ajouter" class="btn">Ajouter un materiel</a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['succes'])): ?>
    <p class="message succes">Operation effectuee avec succes.</p>
<?php endif; ?>

<form method="get" action="index.php" class="form-filtre">
    <input type="hidden" name="page" value="materiels">
    <input type="text" name="filtre" placeholder="Rechercher par nom, serie, categorie..."
           value="<?= htmlspecialchars($filtre) ?>">
    <button type="submit">Rechercher</button>
    <?php if ($filtre !== ''): ?>
        <a href="index.php?page=materiels">Effacer</a>
    <?php endif; ?>
</form>

<?php if (empty($liste)): ?>
    <p>Aucun materiel trouve.</p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>N de serie</th>
            <th>Categorie</th>
            <th>Etat</th>
            <th>Quantite</th>
            <th>Date entree</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($liste as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['nom']) ?></td>
            <td><?= htmlspecialchars($m['numero_serie']) ?></td>
            <td><?= htmlspecialchars($m['categorie']) ?></td>
            <td><span class="badge badge-<?= $m['etat'] ?>"><?= htmlspecialchars($m['etat']) ?></span></td>
            <td><?= $m['quantite'] ?></td>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($m['date_entree']))) ?></td>
            <td>
                <?php if (in_array($_SESSION['utilisateur']['role'], ['administrateur', 'gestionnaire'])): ?>
                    <a href="index.php?page=materiels&action=modifier&id=<?= $m['id'] ?>">Modifier</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
