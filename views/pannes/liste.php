<?php require __DIR__ . '/../layout_top.php'; ?>

<div class="entete-section">
    <h1>Pannes</h1>
    <a href="index.php?page=pannes&action=declarer" class="btn">Declarer une panne</a>
</div>

<?php if ($succes): ?>
    <p class="message succes">Operation effectuee avec succes.</p>
<?php endif; ?>

<?php if (!empty($erreur)): ?>
    <p class="message erreur"><?= htmlspecialchars($erreur) ?></p>
<?php endif; ?>

<?php if (empty($liste)): ?>
    <p>Aucune panne enregistree.</p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>Materiel</th>
            <th>N de serie</th>
            <th>Declare par</th>
            <th>Date</th>
            <th>Description</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($liste as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['materiel_nom']) ?></td>
            <td><?= htmlspecialchars($p['numero_serie']) ?></td>
            <td><?= htmlspecialchars($p['user_prenom'] . ' ' . $p['user_nom']) ?></td>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($p['date_declaration']))) ?></td>
            <td><?= htmlspecialchars($p['description']) ?></td>
            <td><span class="badge badge-<?= $p['statut'] ?>"><?= htmlspecialchars($p['statut']) ?></span></td>
            <td>
                <?php
                $role = $_SESSION['utilisateur']['role'];
                if (in_array($role, ['administrateur', 'gestionnaire'])): ?>
                    <?php if ($p['statut'] === 'ouverte'): ?>
                        <a href="index.php?page=pannes&action=changerStatut&id=<?= $p['id'] ?>&statut=en_cours">Prendre en charge</a>
                    <?php elseif ($p['statut'] === 'en_cours'): ?>
                        <a href="index.php?page=pannes&action=changerStatut&id=<?= $p['id'] ?>&statut=resolue">Marquer resolue</a>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
