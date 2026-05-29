<?php
class MaterielController {

    // Liste des materiels
    public function index(): void {
        $this->lister();
    }

    public function lister(): void {
        $filtre  = trim($_GET['filtre'] ?? '');
        $modele  = new Materiel();
        $liste   = $modele->listerTous($filtre);
        require __DIR__ . '/../views/materiels/liste.php';
    }

    // Formulaire d ajout
    public function ajouter(): void {
        $this->accesGestionnaire();
        $modCat    = new Categorie();
        $categories = $modCat->listerToutes();
        $erreur    = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        require __DIR__ . '/../views/materiels/form.php';
    }

    // Traitement du formulaire d ajout
    public function enregistrer(): void {
        $this->accesGestionnaire();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=materiels');
            exit;
        }

        $donnees = [
            'numero_serie' => trim($_POST['numero_serie'] ?? ''),
            'nom'          => trim($_POST['nom'] ?? ''),
            'description'  => trim($_POST['description'] ?? ''),
            'id_categorie' => (int)($_POST['id_categorie'] ?? 0),
            'quantite'     => (int)($_POST['quantite'] ?? 1),
            'date_entree'  => $_POST['date_entree'] ?? date('Y-m-d'),
        ];

        if ($donnees['numero_serie'] === '' || $donnees['nom'] === '' || $donnees['id_categorie'] === 0) {
            $_SESSION['erreur'] = 'Les champs obligatoires sont manquants.';
            header('Location: index.php?page=materiels&action=ajouter');
            exit;
        }

        $modele    = new Materiel();
        $resultat  = $modele->creer($donnees);

        if ($resultat === true) {
            header('Location: index.php?page=materiels&succes=1');
        } else {
            $_SESSION['erreur'] = is_string($resultat) ? $resultat : 'Erreur lors de l ajout.';
            header('Location: index.php?page=materiels&action=ajouter');
        }
        exit;
    }

    // Formulaire de modification
    public function modifier(): void {
        $this->accesGestionnaire();
        $id      = (int)($_GET['id'] ?? 0);
        $modele  = new Materiel();
        $materiel = $modele->parId($id);

        if (!$materiel) {
            header('Location: index.php?page=materiels');
            exit;
        }

        $modCat     = new Categorie();
        $categories = $modCat->listerToutes();
        $erreur     = $_SESSION['erreur'] ?? null;
        unset($_SESSION['erreur']);
        require __DIR__ . '/../views/materiels/form.php';
    }

    // Traitement de la modification
    public function mettreAJour(): void {
        $this->accesGestionnaire();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=materiels');
            exit;
        }

        $id      = (int)($_POST['id'] ?? 0);
        $donnees = [
            'nom'         => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'id_categorie'=> (int)($_POST['id_categorie'] ?? 0),
            'quantite'    => (int)($_POST['quantite'] ?? 1),
            'etat'        => $_POST['etat'] ?? 'disponible',
        ];

        $modele = new Materiel();
        $modele->modifier($id, $donnees);
        header('Location: index.php?page=materiels&succes=1');
        exit;
    }

    // Restriction aux gestionnaires et administrateurs
    private function accesGestionnaire(): void {
        $role = $_SESSION['utilisateur']['role'] ?? '';
        if (!in_array($role, ['administrateur', 'gestionnaire'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }
    }
}
