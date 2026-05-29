<?php require __DIR__ . '/layout_top.php'; ?>

<h1>Tableau de bord</h1>

<?php
// Calcul des totaux par etat
$totaux = ['disponible' => 0, 'affecte' => 0, 'panne' => 0, 'hors_service' => 0];
foreach ($stats['etats'] as $ligne) {
    $totaux[$ligne['etat']] = $ligne['nb'];
}
?>

<div class="cartes">
    <div class="carte">
        <span class="carte-valeur"><?= $stats['total'] ?></span>
        <span class="carte-label">Total materiels</span>
    </div>
    <div class="carte carte-ok">
        <span class="carte-valeur"><?= $totaux['disponible'] ?></span>
        <span class="carte-label">Disponibles</span>
    </div>
    <div class="carte carte-info">
        <span class="carte-valeur"><?= $totaux['affecte'] ?></span>
        <span class="carte-label">Affectes</span>
    </div>
    <div class="carte carte-alerte">
        <span class="carte-valeur"><?= $totaux['panne'] ?></span>
        <span class="carte-label">En panne</span>
    </div>
    <div class="carte carte-neutre">
        <span class="carte-valeur"><?= $totaux['hors_service'] ?></span>
        <span class="carte-label">Hors service</span>
    </div>
</div>

<?php if (!empty($pannesOuvertes)): ?>
<section>
    <h2>Pannes non resolues</h2>
    <table>
        <thead>
            <tr>
                <th>Materiel</th>
                <th>Declare par</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pannesOuvertes as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['materiel_nom']) ?></td>
                <td><?= htmlspecialchars($p['user_prenom'] . ' ' . $p['user_nom']) ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($p['date_declaration']))) ?></td>
                <td><span class="badge badge-<?= $p['statut'] ?>"><?= htmlspecialchars($p['statut']) ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>

<?php if (!empty($mesAffectations)): ?>
<section>
    <h2>Mes materiels affectes</h2>
    <table>
        <thead>
            <tr>
                <th>Materiel</th>
                <th>Categorie</th>
                <th>N de serie</th>
                <th>Date d affectation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mesAffectations as $a): ?>
            <tr>
                <td><?= htmlspecialchars($a['materiel_nom']) ?></td>
                <td><?= htmlspecialchars($a['categorie']) ?></td>
                <td><?= htmlspecialchars($a['numero_serie']) ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($a['date_affectation']))) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>

<?php require __DIR__ . '/layout_bottom.php'; ?>
