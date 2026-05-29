<?php
require_once __DIR__ . '/Model.php';

class Panne extends Model {

    public function listerToutes(): array {
        $req = $this->db->query(
            'SELECT p.*, m.nom AS materiel_nom, m.numero_serie,
                    u.nom AS user_nom, u.prenom AS user_prenom
             FROM panne p
             JOIN materiel m ON p.id_materiel = m.id
             JOIN utilisateur u ON p.id_declarant = u.id
             ORDER BY p.date_declaration DESC'
        );
        return $req->fetchAll();
    }

    // Declarer une panne : met le materiel en etat "panne"
    public function declarer(int $idMateriel, int $idDeclarant, string $description): bool|string {
        $req = $this->db->prepare('SELECT etat FROM materiel WHERE id = ?');
        $req->execute([$idMateriel]);
        $materiel = $req->fetch();

        if (!$materiel) {
            return 'Materiel introuvable.';
        }

        $req = $this->db->prepare(
            'INSERT INTO panne (id_materiel, id_declarant, description) VALUES (?,?,?)'
        );
        $req->execute([$idMateriel, $idDeclarant, $description]);

        // Mettre le materiel en panne
        $req = $this->db->prepare("UPDATE materiel SET etat='panne' WHERE id=?");
        $req->execute([$idMateriel]);

        return true;
    }

    // Changer le statut d une panne (ex: resolue -> remettre disponible)
    public function changerStatut(int $idPanne, string $statut): bool|string {
        $req = $this->db->prepare('SELECT id_materiel FROM panne WHERE id = ?');
        $req->execute([$idPanne]);
        $panne = $req->fetch();

        if (!$panne) {
            return 'Panne introuvable.';
        }

        $req = $this->db->prepare('UPDATE panne SET statut=? WHERE id=?');
        $req->execute([$statut, $idPanne]);

        // Si resolue, remettre le materiel disponible
        if ($statut === 'resolue') {
            $req = $this->db->prepare("UPDATE materiel SET etat='disponible' WHERE id=?");
            $req->execute([$panne['id_materiel']]);
        }

        return true;
    }
}
