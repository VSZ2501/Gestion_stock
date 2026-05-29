<?php
require_once __DIR__ . '/Model.php';

class Affectation extends Model {

    // Affecter un materiel a un employe
    // Regles : materiel en panne ne peut pas etre affecte
    //          materiel deja affecte ne peut pas l etre a nouveau
    public function affecter(int $idMateriel, int $idUtilisateur, string $date, string $commentaire): bool|string {
        // Verifier l etat du materiel
        $req = $this->db->prepare('SELECT etat FROM materiel WHERE id = ?');
        $req->execute([$idMateriel]);
        $materiel = $req->fetch();

        if (!$materiel) {
            return 'Materiel introuvable.';
        }
        if ($materiel['etat'] === 'panne') {
            return 'Un materiel en panne ne peut pas etre affecte.';
        }
        if ($materiel['etat'] === 'affecte') {
            return 'Ce materiel est deja affecte a un employe.';
        }
        if ($materiel['etat'] === 'hors_service') {
            return 'Ce materiel est hors service.';
        }

        // Enregistrer l affectation
        $req = $this->db->prepare(
            'INSERT INTO affectation (id_materiel, id_utilisateur, date_affectation, commentaire)
             VALUES (?,?,?,?)'
        );
        $req->execute([$idMateriel, $idUtilisateur, $date, $commentaire]);

        // Mettre a jour l etat du materiel
        $req = $this->db->prepare("UPDATE materiel SET etat='affecte' WHERE id=?");
        $req->execute([$idMateriel]);

        return true;
    }

    // Enregistrer un retour de materiel
    public function retourner(int $idAffectation, string $dateRetour, string $commentaire): bool|string {
        $req = $this->db->prepare(
            'SELECT a.*, m.etat FROM affectation a
             JOIN materiel m ON a.id_materiel = m.id
             WHERE a.id = ? AND a.date_retour IS NULL'
        );
        $req->execute([$idAffectation]);
        $affectation = $req->fetch();

        if (!$affectation) {
            return 'Affectation introuvable ou deja cloturee.';
        }

        // Enregistrer la date de retour
        $req = $this->db->prepare(
            'UPDATE affectation SET date_retour=?, commentaire=CONCAT(IFNULL(commentaire,"")," | Retour: ",?) WHERE id=?'
        );
        $req->execute([$dateRetour, $commentaire, $idAffectation]);

        // Remettre le materiel disponible
        $req = $this->db->prepare("UPDATE materiel SET etat='disponible' WHERE id=?");
        $req->execute([$affectation['id_materiel']]);

        return true;
    }

    // Lister toutes les affectations (historique)
    public function listerToutes(): array {
        $req = $this->db->query(
            'SELECT a.*,
                    m.nom AS materiel_nom, m.numero_serie,
                    u.nom AS user_nom, u.prenom AS user_prenom
             FROM affectation a
             JOIN materiel m ON a.id_materiel = m.id
             JOIN utilisateur u ON a.id_utilisateur = u.id
             ORDER BY a.date_affectation DESC'
        );
        return $req->fetchAll();
    }

    // Lister les affectations actives d un employe
    public function parUtilisateur(int $idUtilisateur): array {
        $req = $this->db->prepare(
            'SELECT a.*, m.nom AS materiel_nom, m.numero_serie, c.libelle AS categorie
             FROM affectation a
             JOIN materiel m ON a.id_materiel = m.id
             JOIN categorie c ON m.id_categorie = c.id
             WHERE a.id_utilisateur = ? AND a.date_retour IS NULL
             ORDER BY a.date_affectation DESC'
        );
        $req->execute([$idUtilisateur]);
        return $req->fetchAll();
    }
}
