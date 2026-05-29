<?php
class CategorieController {

    public function index(): void {
        $this->lister();
    }

    public function lister(): void {
        $this->accesAdmin();
        $modele = new Categorie();
        $liste  = $modele->listerToutes();
        $erreur  = $_SESSION['erreur'] ?? null;
        $succes  = isset($_GET['succes']);
        unset($_SESSION['erreur']);
        require __DIR__ . '/../views/categories/liste.php';
    }

    public function enregistrer(): void {
        $this->accesAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=categories');
            exit;
        }

        $libelle  = trim($_POST['libelle'] ?? '');
        $id       = (int)($_POST['id'] ?? 0);

        if ($libelle === '') {
            $_SESSION['erreur'] = 'Le libelle est obligatoire.';
            header('Location: index.php?page=categories');
            exit;
        }

        $modele   = new Categorie();
        $resultat = ($id > 0)
            ? $modele->modifier($id, $libelle)
            : $modele->creer($libelle);

        if ($resultat === true || $resultat === 1) {
            header('Location: index.php?page=categories&succes=1');
        } else {
            $_SESSION['erreur'] = is_string($resultat) ? $resultat : 'Erreur.';
            header('Location: index.php?page=categories');
        }
        exit;
    }

    public function supprimer(): void {
        $this->accesAdmin();
        $id       = (int)($_GET['id'] ?? 0);
        $modele   = new Categorie();
        $resultat = $modele->supprimer($id);

        if (is_string($resultat)) {
            $_SESSION['erreur'] = $resultat;
        }
        header('Location: index.php?page=categories');
        exit;
    }

    private function accesAdmin(): void {
        if (($_SESSION['utilisateur']['role'] ?? '') !== 'administrateur') {
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
