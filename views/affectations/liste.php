<?php require __DIR__ . '/../layout_top.php'; ?>

<div class="entete-section">
    <h1>Affectations</h1>
    <?php if (in_array($_SESSION['utilisateur']['role'], ['administrateur', 'gestionnaire'])): ?>
        <a href="index.php?page=affectations&action=affecter" class="btn">Nouvelle affectation</a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['succes'])): ?>
    <p class="message succes">Operation effectuee avec succes.</p>
<?php endif; ?>

<?php if (empty($liste)): ?>
    <p>Aucune affectation enregistree.</p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>Materiel</th>
            <th>N de serie</th>
            <th>Employe</th>
            <th>Date affectation</th>
            <th>Date retour</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($liste as $a): ?>
        <tr class="<?= $a['date_retour'] ? 'ligne-retournee' : '' ?>">
            <td><?= htmlspecialchars($a['materiel_nom']) ?></td>
            <td><?= htmlspecialchars($a['numero_serie']) ?></td>
            <td><?= htmlspecialchars($a['user_prenom'] . ' ' . $a['user_nom']) ?></td>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($a['date_affectation']))) ?></td>
            <td><?= $a['date_retour'] ? htmlspecialchars(date('d/m/Y', strtotime($a['date_retour']))) : 'En cours' ?></td>
            <td>
                <?php if (!$a['date_retour'] && in_array($_SESSION['utilisateur']['role'], ['administrateur', 'gestionnaire'])): ?>
                    <a href="index.php?page=affectations&action=retour&id=<?= $a['id'] ?>">Enregistrer retour</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
