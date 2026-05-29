<?php
class AuthController {

    public function index(): void {
        $this->login();
    }

    // Afficher le formulaire de connexion
    public function login(): void {
        if (!empty($_SESSION['utilisateur'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        $erreur = $_SESSION['erreur_login'] ?? null;
        unset($_SESSION['erreur_login']);
        require __DIR__ . '/../views/login.php';
    }

    // Traiter la soumission du formulaire
    public function connecter(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['mot_de_passe'] ?? '';

        if ($email === '' || $mdp === '') {
            $_SESSION['erreur_login'] = 'Veuillez remplir tous les champs.';
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        $modele = new Utilisateur();
        $user   = $modele->parEmail($email);

        if (!$user || !password_verify($mdp, $user['mot_de_passe'])) {
            $_SESSION['erreur_login'] = 'Email ou mot de passe incorrect.';
            header('Location: index.php?page=auth&action=login');
            exit;
        }

        // Stocker les infos de session (sans le mot de passe)
        $_SESSION['utilisateur'] = [
            'id'     => $user['id'],
            'nom'    => $user['nom'],
            'prenom' => $user['prenom'],
            'email'  => $user['email'],
            'role'   => $user['role'],
        ];

        header('Location: index.php?page=dashboard');
        exit;
    }

    // Deconnexion
    public function deconnecter(): void {
        session_destroy();
        header('Location: index.php?page=auth&action=login');
        exit;
    }
}
