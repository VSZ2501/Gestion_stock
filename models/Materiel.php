<?php
require_once __DIR__ . '/Model.php';

class Materiel extends Model {

    // Lister tous les materiels avec leur categorie
    public function listerTous(string $filtre = ''): array {
        $sql = 'SELECT m.*, c.libelle AS categorie FROM materiel m
                JOIN categorie c ON m.id_categorie = c.id';
        if ($filtre !== '') {
            $sql .= ' WHERE m.nom LIKE ? OR m.numero_serie LIKE ? OR c.libelle LIKE ?';
            $motif = '%' . $filtre . '%';
            $req   = $this->db->prepare($sql . ' ORDER BY m.nom');
            $req->execute([$motif, $motif, $motif]);
            return $req->fetchAll();
        }
        return $this->db->query($sql . ' ORDER BY m.nom')->fetchAll();
    }

    // Trouver un materiel par son id
    public function parId(int $id): array|false {
        $req = $this->db->prepare(
            'SELECT m.*, c.libelle AS categorie FROM materiel m
             JOIN categorie c ON m.id_categorie = c.id WHERE m.id = ?'
        );
        $req->execute([$id]);
        return $req->fetch();
    }

    // Ajouter un materiel
    // Regle : numero de serie unique
    public function creer(array $donnees): bool|string {
        $req = $this->db->prepare('SELECT id FROM materiel WHERE numero_serie = ?');
        $req->execute([$donnees['numero_serie']]);
        if ($req->fetch()) {
            return 'Ce numero de serie est deja utilise.';
        }
        $req = $this->db->prepare(
            'INSERT INTO materiel (numero_serie, nom, description, id_categorie, quantite, date_entree)
             VALUES (?,?,?,?,?,?)'
        );
        return $req->execute([
            $donnees['numero_serie'],
            $donnees['nom'],
            $donnees['description'],
            $donnees['id_categorie'],
            $donnees['quantite'],
            $donnees['date_entree'],
        ]);
    }

    // Modifier un materiel
    public function modifier(int $id, array $donnees): bool {
        $req = $this->db->prepare(
            'UPDATE materiel SET nom=?, description=?, id_categorie=?, quantite=?, etat=? WHERE id=?'
        );
        return $req->execute([
            $donnees['nom'],
            $donnees['description'],
            $donnees['id_categorie'],
            $donnees['quantite'],
            $donnees['etat'],
            $id,
        ]);
    }

    // Changer l etat d un materiel
    public function changerEtat(int $id, string $etat): bool {
        $req = $this->db->prepare('UPDATE materiel SET etat=? WHERE id=?');
        return $req->execute([$etat, $id]);
    }

    // Lister les materiels disponibles (pour une affectation)
    public function disponibles(): array {
        $req = $this->db->query(
            "SELECT m.*, c.libelle AS categorie FROM materiel m
             JOIN categorie c ON m.id_categorie = c.id
             WHERE m.etat = 'disponible' ORDER BY m.nom"
        );
        return $req->fetchAll();
    }

    // Statistiques pour le tableau de bord
    public function statistiques(): array {
        $etats = $this->db->query(
            "SELECT etat, COUNT(*) AS nb FROM materiel GROUP BY etat"
        )->fetchAll();
        $total = $this->db->query('SELECT COUNT(*) AS nb FROM materiel')->fetch()['nb'];
        return ['etats' => $etats, 'total' => $total];
    }
}
