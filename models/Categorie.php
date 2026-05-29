<?php
require_once __DIR__ . '/Model.php';

class Categorie extends Model {

    public function listerToutes(): array {
        return $this->db->query('SELECT * FROM categorie ORDER BY libelle')->fetchAll();
    }

    public function parId(int $id): array|false {
        $req = $this->db->prepare('SELECT * FROM categorie WHERE id = ?');
        $req->execute([$id]);
        return $req->fetch();
    }

    public function creer(string $libelle): bool|string {
        $req = $this->db->prepare('SELECT id FROM categorie WHERE libelle = ?');
        $req->execute([$libelle]);
        if ($req->fetch()) {
            return 'Cette categorie existe deja.';
        }
        $req = $this->db->prepare('INSERT INTO categorie (libelle) VALUES (?)');
        return $req->execute([$libelle]);
    }

    public function modifier(int $id, string $libelle): bool {
        $req = $this->db->prepare('UPDATE categorie SET libelle=? WHERE id=?');
        return $req->execute([$libelle, $id]);
    }

    public function supprimer(int $id): bool|string {
        // Verifier qu aucun materiel n utilise cette categorie
        $req = $this->db->prepare('SELECT COUNT(*) AS nb FROM materiel WHERE id_categorie = ?');
        $req->execute([$id]);
        if ($req->fetch()['nb'] > 0) {
            return 'Impossible de supprimer : des materiels utilisent cette categorie.';
        }
        $req = $this->db->prepare('DELETE FROM categorie WHERE id = ?');
        return $req->execute([$id]);
    }
}
