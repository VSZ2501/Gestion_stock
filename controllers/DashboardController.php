<?php
class DashboardController {

    public function index(): void {
        $modMateriel    = new Materiel();
        $modAffectation = new Affectation();
        $modPanne       = new Panne();

        $stats          = $modMateriel->statistiques();
        $pannesOuvertes = array_filter(
            $modPanne->listerToutes(),
            fn($p) => $p['statut'] !== 'resolue'
        );

        // Pour un employe : montrer ses propres affectations
        $user = $_SESSION['utilisateur'];
        $mesAffectations = [];
        if ($user['role'] === 'employe') {
            $modAff          = new Affectation();
            $mesAffectations = $modAff->parUtilisateur($user['id']);
        }

        require __DIR__ . '/../views/dashboard.php';
    }
}
