<?php
class UtilisateurController {

    public function index(): void {
        $this->lister();
    }

    public function lister(): void {
        $this->accesAdmin();
        $modele = new Utilisateur();
        $liste  = $modele->listerTous();
        require __DIR__ . '/../views/utilisateurs/liste.php';
    }

    public function ajouter(): void {
        $this->accesAdmin();
        $modele = new Utilisateur();
        $roles  = $modele->listerRoles();
        $erreur = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        $utilisateur = null;
        require __DIR__ . '/../views/utilisateurs/form.php';
    }

    public function enregistrer(): void {
        $this->accesAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=utilisateurs');
            exit;
        }

        $nom    = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $mdp    = $_POST['mot_de_passe'] ?? '';
        $idRole = (int)($_POST['id_role'] ?? 0);

        if ($nom === '' || $prenom === '' || $email === '' || $mdp === '' || $idRole === 0) {
            $_SESSION['erreur'] = 'Tous les champs sont obligatoires.';
            header('Location: index.php?page=utilisateurs&action=ajouter');
            exit;
        }

        $modele   = new Utilisateur();
        $resultat = $modele->creer($nom, $prenom, $email, $mdp, $idRole);

        if ($resultat === true) {
            header('Location: index.php?page=utilisateurs&succes=1');
        } else {
            $_SESSION['erreur'] = is_string($resultat) ? $resultat : 'Erreur lors de la creation.';
            header('Location: index.php?page=utilisateurs&action=ajouter');
        }
        exit;
    }

    public function modifier(): void {
        $this->accesAdmin();
        $id     = (int)($_GET['id'] ?? 0);
        $modele = new Utilisateur();
        $utilisateur = $modele->parId($id);

        if (!$utilisateur) {
            header('Location: index.php?page=utilisateurs');
            exit;
        }

        $roles  = $modele->listerRoles();
        $erreur = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        require __DIR__ . '/../views/utilisateurs/form.php';
    }

    public function mettreAJour(): void {
        $this->accesAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=utilisateurs');
            exit;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $nom    = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $idRole = (int)($_POST['id_role'] ?? 0);

        $modele = new Utilisateur();
        $modele->modifier($id, $nom, $prenom, $email, $idRole);
        header('Location: index.php?page=utilisateurs&succes=1');
        exit;
    }

    public function desactiver(): void {
        $this->accesAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $modele = new Utilisateur();
        $modele->desactiver($id);
        header('Location: index.php?page=utilisateurs');
        exit;
    }

    private function accesAdmin(): void {
        if (($_SESSION['utilisateur']['role'] ?? '') !== 'administrateur') {
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
