<?php
require_once __DIR__ . '/Model.php';

class Utilisateur extends Model {

    // Chercher un utilisateur par email (pour la connexion)
    public function parEmail(string $email): array|false {
        $req = $this->db->prepare(
            'SELECT u.*, r.libelle AS role FROM utilisateur u
             JOIN role r ON u.id_role = r.id
             WHERE u.email = ? AND u.actif = 1'
        );
        $req->execute([$email]);
        return $req->fetch();
    }

    // Lister tous les utilisateurs
    public function listerTous(): array {
        $req = $this->db->query(
            'SELECT u.*, r.libelle AS role FROM utilisateur u
             JOIN role r ON u.id_role = r.id
             ORDER BY u.nom, u.prenom'
        );
        return $req->fetchAll();
    }

    // Trouver un utilisateur par son id
    public function parId(int $id): array|false {
        $req = $this->db->prepare(
            'SELECT u.*, r.libelle AS role FROM utilisateur u
             JOIN role r ON u.id_role = r.id WHERE u.id = ?'
        );
        $req->execute([$id]);
        return $req->fetch();
    }

    // Creer un utilisateur
    public function creer(string $nom, string $prenom, string $email, string $mdp, int $idRole): bool {
        // Verifier unicite de l email
        $req = $this->db->prepare('SELECT id FROM utilisateur WHERE email = ?');
        $req->execute([$email]);
        if ($req->fetch()) {
            return false; // Email deja utilise
        }
        $hash = password_hash($mdp, PASSWORD_DEFAULT);
        $req  = $this->db->prepare(
            'INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, id_role) VALUES (?,?,?,?,?)'
        );
        return $req->execute([$nom, $prenom, $email, $hash, $idRole]);
    }

    // Modifier un utilisateur
    public function modifier(int $id, string $nom, string $prenom, string $email, int $idRole): bool {
        $req = $this->db->prepare(
            'UPDATE utilisateur SET nom=?, prenom=?, email=?, id_role=? WHERE id=?'
        );
        return $req->execute([$nom, $prenom, $email, $idRole, $id]);
    }

    // Desactiver un utilisateur (suppression douce)
    public function desactiver(int $id): bool {
        $req = $this->db->prepare('UPDATE utilisateur SET actif=0 WHERE id=?');
        return $req->execute([$id]);
    }

    // Lister les roles disponibles
    public function listerRoles(): array {
        return $this->db->query('SELECT * FROM role ORDER BY id')->fetchAll();
    }
}
