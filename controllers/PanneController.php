<?php
class PanneController {

    public function index(): void {
        $this->lister();
    }

    public function lister(): void {
        $modele = new Panne();
        $liste  = $modele->listerToutes();
        $erreur = $_SESSION['erreur'] ?? null;
        $succes = isset($_GET['succes']);
        unset($_SESSION['erreur']);
        require __DIR__ . '/../views/pannes/liste.php';
    }

    public function declarer(): void {
        $modMat    = new Materiel();
        $materiels = $modMat->listerTous();
        $erreur    = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        require __DIR__ . '/../views/pannes/form.php';
    }

    public function enregistrer(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pannes');
            exit;
        }

        $idMateriel  = (int)($_POST['id_materiel'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $idDeclarant = $_SESSION['utilisateur']['id'];

        if ($idMateriel === 0 || $description === '') {
            $_SESSION['erreur'] = 'Materiel et description sont obligatoires.';
            header('Location: index.php?page=pannes&action=declarer');
            exit;
        }

        $modele   = new Panne();
        $resultat = $modele->declarer($idMateriel, $idDeclarant, $description);

        if ($resultat === true) {
            header('Location: index.php?page=pannes&succes=1');
        } else {
            $_SESSION['erreur'] = is_string($resultat) ? $resultat : 'Erreur lors de la declaration.';
            header('Location: index.php?page=pannes&action=declarer');
        }
        exit;
    }

    public function changerStatut(): void {
        // Seul le gestionnaire ou admin peut changer le statut
        $role = $_SESSION['utilisateur']['role'] ?? '';
        if (!in_array($role, ['administrateur', 'gestionnaire'])) {
            header('Location: index.php?page=pannes');
            exit;
        }

        $id     = (int)($_GET['id'] ?? 0);
        $statut = $_GET['statut'] ?? '';

        if (!in_array($statut, ['ouverte', 'en_cours', 'resolue'])) {
            header('Location: index.php?page=pannes');
            exit;
        }

        $modele = new Panne();
        $modele->changerStatut($id, $statut);
        header('Location: index.php?page=pannes&succes=1');
        exit;
    }
}
