<?php
class AffectationController {

    public function index(): void {
        $this->lister();
    }

    public function lister(): void {
        $modele = new Affectation();
        $liste  = $modele->listerToutes();
        require __DIR__ . '/../views/affectations/liste.php';
    }

    public function affecter(): void {
        $this->accesGestionnaire();
        $modMat    = new Materiel();
        $modUser   = new Utilisateur();
        $materiels = $modMat->disponibles();
        $employes  = array_filter(
            $modUser->listerTous(),
            fn($u) => $u['role'] === 'employe'
        );
        $erreur = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        require __DIR__ . '/../views/affectations/form.php';
    }

    public function enregistrer(): void {
        $this->accesGestionnaire();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=affectations');
            exit;
        }

        $idMateriel    = (int)($_POST['id_materiel'] ?? 0);
        $idUtilisateur = (int)($_POST['id_utilisateur'] ?? 0);
        $date          = $_POST['date_affectation'] ?? date('Y-m-d');
        $commentaire   = trim($_POST['commentaire'] ?? '');

        $modele   = new Affectation();
        $resultat = $modele->affecter($idMateriel, $idUtilisateur, $date, $commentaire);

        if ($resultat === true) {
            header('Location: index.php?page=affectations&succes=1');
        } else {
            $_SESSION['erreur'] = is_string($resultat) ? $resultat : 'Erreur lors de l affectation.';
            header('Location: index.php?page=affectations&action=affecter');
        }
        exit;
    }

    // Formulaire de retour
    public function retour(): void {
        $this->accesGestionnaire();
        $id     = (int)($_GET['id'] ?? 0);
        $erreur = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        require __DIR__ . '/../views/affectations/retour.php';
    }

    public function enregistrerRetour(): void {
        $this->accesGestionnaire();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=affectations');
            exit;
        }

        $idAffectation = (int)($_POST['id'] ?? 0);
        $dateRetour    = $_POST['date_retour'] ?? date('Y-m-d');
        $commentaire   = trim($_POST['commentaire'] ?? '');

        $modele   = new Affectation();
        $resultat = $modele->retourner($idAffectation, $dateRetour, $commentaire);

        if ($resultat === true) {
            header('Location: index.php?page=affectations&succes=1');
        } else {
            $_SESSION['erreur'] = is_string($resultat) ? $resultat : 'Erreur lors du retour.';
            header('Location: index.php?page=affectations&action=retour&id=' . $idAffectation);
        }
        exit;
    }

    private function accesGestionnaire(): void {
        $role = $_SESSION['utilisateur']['role'] ?? '';
        if (!in_array($role, ['administrateur', 'gestionnaire'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
