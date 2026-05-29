<?php
// Point d entree unique de l application
// Toutes les requetes passent par ce fichier via .htaccess

session_start();
require_once __DIR__ . '/config.php';

// Chargement automatique des classes
spl_autoload_register(function (string $classe) {
    $dossiers = [
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
    ];
    foreach ($dossiers as $dossier) {
        $fichier = $dossier . $classe . '.php';
        if (file_exists($fichier)) {
            require_once $fichier;
            return;
        }
    }
});

// Lecture de la route depuis l URL : ?page=dashboard&action=index
$page   = $_GET['page']   ?? 'auth';
$action = $_GET['action'] ?? 'index';

// Pages accessibles sans connexion
$pagesPubliques = ['auth'];

// Verification de la session
if (!in_array($page, $pagesPubliques) && empty($_SESSION['utilisateur'])) {
    header('Location: index.php?page=auth&action=login');
    exit;
}

// Routage vers le bon controleur
$controleurs = [
    'auth'         => 'AuthController',
    'dashboard'    => 'DashboardController',
    'materiels'    => 'MaterielController',
    'utilisateurs' => 'UtilisateurController',
    'affectations' => 'AffectationController',
    'categories'   => 'CategorieController',
    'pannes'       => 'PanneController',
];

if (!isset($controleurs[$page])) {
    // Page inconnue : rediriger vers le tableau de bord
    header('Location: index.php?page=dashboard');
    exit;
}

$nomControleur = $controleurs[$page];
$controleur    = new $nomControleur();

// Verifier que la methode demandee existe
if (!method_exists($controleur, $action)) {
    header('Location: index.php?page=dashboard');
    exit;
}

$controleur->$action();
