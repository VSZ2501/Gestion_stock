<?php require __DIR__ . '/../layout_top.php'; ?>

<div class="entete-section">
    <h1>Utilisateurs</h1>
    <a href="index.php?page=utilisateurs&action=ajouter" class="btn">Ajouter un utilisateur</a>
</div>

<?php if (isset($_GET['succes'])): ?>
    <p class="message succes">Operation effectuee avec succes.</p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Email</th>
            <th>Role</th>
            <th>Etat</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($liste as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
            <td><?= $u['actif'] ? 'Actif' : 'Desactive' ?></td>
            <td>
                <a href="index.php?page=utilisateurs&action=modifier&id=<?= $u['id'] ?>">Modifier</a>
                <?php if ($u['id'] !== $_SESSION['utilisateur']['id']): ?>
                    &mdash;
                    <a href="index.php?page=utilisateurs&action=desactiver&id=<?= $u['id'] ?>"
                       onclick="return confirm('Desactiver cet utilisateur ?')">Desactiver</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layout_bottom.php'; ?>
