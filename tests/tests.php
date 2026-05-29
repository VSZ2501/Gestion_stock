<?php
/**
 * Tests unitaires - Gestion de stock informatique
 *
 * Methodologie (cours Tests Unitaires) :
 *   - Chaque test valide un module independamment des autres (modele seul).
 *   - On distingue les cas nominaux (chemin normal) et les cas d erreur
 *     (cas limites, violations de regles de gestion).
 *   - Le rapport liste chaque test : nom, resultat PASS / FAIL, message.
 *
 * Pour lancer :  php tests/tests.php
 * Prerequis     : la base de donnees doit etre accessible et vide (ou de test).
 *                 Executer d abord database.sql avec les donnees de test.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../models/Materiel.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Affectation.php';
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../models/Panne.php';

// -----------------------------------------------------------------
// Mini-framework de test (sans dependance externe)
// -----------------------------------------------------------------

$rapport = [];
$nbPass  = 0;
$nbFail  = 0;

/**
 * Enregistre un test et affiche son resultat.
 * $attendu et $obtenu sont compares avec ===.
 */
function tester(string $nom, mixed $attendu, mixed $obtenu): void {
    global $rapport, $nbPass, $nbFail;

    $ok = ($attendu === $obtenu);

    if ($ok) {
        $nbPass++;
        $statut = 'PASS';
    } else {
        $nbFail++;
        $statut = 'FAIL';
    }

    $rapport[] = [
        'nom'    => $nom,
        'statut' => $statut,
        'info'   => $ok ? '' : "Attendu: " . var_export($attendu, true) . " | Obtenu: " . var_export($obtenu, true),
    ];
}

/**
 * Verifie qu une valeur est truthy (bool true ou entier > 0).
 */
function testerVrai(string $nom, mixed $obtenu): void {
    tester($nom, true, (bool)$obtenu);
}

/**
 * Verifie que la valeur est une chaine (message d erreur).
 */
function testerErreur(string $nom, mixed $obtenu): void {
    global $rapport, $nbPass, $nbFail;
    $ok = is_string($obtenu) && strlen($obtenu) > 0;
    if ($ok) { $nbPass++; $statut = 'PASS'; }
    else      { $nbFail++; $statut = 'FAIL'; }
    $rapport[] = [
        'nom'    => $nom,
        'statut' => $statut,
        'info'   => $ok ? '' : "Attendu un message d erreur, obtenu: " . var_export($obtenu, true),
    ];
}

// -----------------------------------------------------------------
// Preparation : nettoyer les donnees de test precedentes
// -----------------------------------------------------------------

$db = getDB();

// Supprimer dans l ordre des dependances (cles etrangeres)
$db->exec("DELETE FROM panne       WHERE id_declarant IN (SELECT id FROM utilisateur WHERE email LIKE '%@test.local')");
$db->exec("DELETE FROM affectation WHERE id_utilisateur IN (SELECT id FROM utilisateur WHERE email LIKE '%@test.local')");
$db->exec("DELETE FROM mouvement   WHERE id_utilisateur IN (SELECT id FROM utilisateur WHERE email LIKE '%@test.local')");
$db->exec("DELETE FROM utilisateur WHERE email LIKE '%@test.local'");
$db->exec("DELETE FROM materiel    WHERE numero_serie LIKE 'TEST-%'");
$db->exec("DELETE FROM categorie   WHERE libelle = 'Categorie Test'");

// -----------------------------------------------------------------
// GROUPE 1 : Gestion des utilisateurs
// -----------------------------------------------------------------

echo "=== Tests Utilisateur ===\n";

$modUser = new Utilisateur();

// T01 - Creation d un utilisateur valide
$ok = $modUser->creer('Test', 'Unitaire', 'u1@test.local', 'monmdp', 2);
testerVrai('T01 - Creation utilisateur valide', $ok);

// T02 - Email deja utilise doit renvoyer false
$doublon = $modUser->creer('Test', 'Bis', 'u1@test.local', 'monmdp', 2);
tester('T02 - Email duplique refuse', false, $doublon);

// T03 - Recherche par email retourne l utilisateur cree
$trouve = $modUser->parEmail('u1@test.local');
tester('T03 - Recherche par email', 'Test', $trouve['nom'] ?? null);

// T04 - Verification du mot de passe hache
testerVrai('T04 - Mot de passe hache correctement', password_verify('monmdp', $trouve['mot_de_passe']));

// T05 - Lister tous retourne au moins un element
$liste = $modUser->listerTous();
testerVrai('T05 - Liste utilisateurs non vide', count($liste) > 0);

// -----------------------------------------------------------------
// GROUPE 2 : Gestion des categories
// -----------------------------------------------------------------

echo "\n=== Tests Categorie ===\n";

$modCat = new Categorie();

// T06 - Creation d une categorie valide
$ok = $modCat->creer('Categorie Test');
testerVrai('T06 - Creation categorie valide', $ok);

// T07 - Categorie dupliquee refusee
$doublon = $modCat->creer('Categorie Test');
testerErreur('T07 - Categorie dupliquee refusee', $doublon);

// -----------------------------------------------------------------
// GROUPE 3 : Gestion du materiel
// -----------------------------------------------------------------

echo "\n=== Tests Materiel ===\n";

$modMat = new Materiel();

// Recuperer l id de la categorie de test
$cat    = $db->query("SELECT id FROM categorie WHERE libelle = 'Categorie Test'")->fetch();
$idCat  = $cat['id'];

// T08 - Creation d un materiel valide
$ok = $modMat->creer([
    'numero_serie' => 'TEST-001',
    'nom'          => 'Materiel de test',
    'description'  => 'Pour les tests unitaires',
    'id_categorie' => $idCat,
    'quantite'     => 1,
    'date_entree'  => date('Y-m-d'),
]);
testerVrai('T08 - Creation materiel valide', $ok);

// T09 - Numero de serie unique (doublon refuse)
$doublon = $modMat->creer([
    'numero_serie' => 'TEST-001',
    'nom'          => 'Autre materiel',
    'description'  => '',
    'id_categorie' => $idCat,
    'quantite'     => 1,
    'date_entree'  => date('Y-m-d'),
]);
testerErreur('T09 - Numero de serie duplique refuse', $doublon);

// Recuperer l id du materiel cree
$matTest = $db->query("SELECT id FROM materiel WHERE numero_serie = 'TEST-001'")->fetch();
$idMat   = $matTest['id'];

// T10 - Recherche par id retourne le bon materiel
$m = $modMat->parId($idMat);
tester('T10 - Recherche materiel par id', 'TEST-001', $m['numero_serie'] ?? null);

// T11 - Etat initial est "disponible"
tester('T11 - Etat initial disponible', 'disponible', $m['etat'] ?? null);

// -----------------------------------------------------------------
// GROUPE 4 : Affectations et regles de gestion
// -----------------------------------------------------------------

echo "\n=== Tests Affectation (regles de gestion) ===\n";

$modAff  = new Affectation();
$userCree = $modUser->parEmail('u1@test.local');
$idUser   = $userCree['id'];

// T12 - Affectation valide (materiel disponible)
$ok = $modAff->affecter($idMat, $idUser, date('Y-m-d'), 'Test affectation');
testerVrai('T12 - Affectation d un materiel disponible', $ok);

// T13 - Verifier que l etat est passe a "affecte"
$m = $modMat->parId($idMat);
tester('T13 - Etat passe a affecte apres affectation', 'affecte', $m['etat'] ?? null);

// T14 - Un materiel deja affecte ne peut pas l etre a nouveau
$doublon = $modAff->affecter($idMat, $idUser, date('Y-m-d'), '');
testerErreur('T14 - Materiel deja affecte refuse', $doublon);

// T15 - Retour : le materiel repasse disponible
$aff = $db->query("SELECT id FROM affectation WHERE id_materiel = $idMat ORDER BY id DESC LIMIT 1")->fetch();
$ok  = $modAff->retourner($aff['id'], date('Y-m-d'), 'Retour test');
testerVrai('T15 - Enregistrement du retour', $ok);

$m = $modMat->parId($idMat);
tester('T16 - Etat repasse disponible apres retour', 'disponible', $m['etat'] ?? null);

// -----------------------------------------------------------------
// GROUPE 5 : Pannes
// -----------------------------------------------------------------

echo "\n=== Tests Panne ===\n";

$modPanne = new Panne();

// T17 - Declaration d une panne valide
$ok = $modPanne->declarer($idMat, $idUser, 'Panne test unitaire');
testerVrai('T17 - Declaration de panne valide', $ok);

// T18 - Le materiel passe en etat "panne"
$m = $modMat->parId($idMat);
tester('T18 - Etat passe a panne', 'panne', $m['etat'] ?? null);

// T19 - Un materiel en panne ne peut pas etre affecte
$refus = $modAff->affecter($idMat, $idUser, date('Y-m-d'), '');
testerErreur('T19 - Materiel en panne non affectable', $refus);

// T20 - Resolution de la panne remet le materiel disponible
$panne = $db->query("SELECT id FROM panne WHERE id_materiel = $idMat ORDER BY id DESC LIMIT 1")->fetch();
$ok    = $modPanne->changerStatut($panne['id'], 'resolue');
testerVrai('T20 - Resolution de panne', $ok);

$m = $modMat->parId($idMat);
tester('T21 - Materiel disponible apres resolution panne', 'disponible', $m['etat'] ?? null);

// -----------------------------------------------------------------
// Nettoyage apres tests
// -----------------------------------------------------------------

$db->exec("DELETE FROM panne       WHERE id_declarant = $idUser");
$db->exec("DELETE FROM affectation WHERE id_utilisateur = $idUser");
$db->exec("DELETE FROM materiel    WHERE numero_serie LIKE 'TEST-%'");
$db->exec("DELETE FROM utilisateur WHERE email LIKE '%@test.local'");
$db->exec("DELETE FROM categorie   WHERE libelle = 'Categorie Test'");

// -----------------------------------------------------------------
// Rapport final
// -----------------------------------------------------------------

echo "\n========================================\n";
echo "RAPPORT DES TESTS\n";
echo "========================================\n";

foreach ($rapport as $t) {
    $ligne = sprintf("%-45s %s", $t['nom'], $t['statut']);
    if ($t['info']) {
        $ligne .= "\n    --> " . $t['info'];
    }
    echo $ligne . "\n";
}

echo "----------------------------------------\n";
echo "Total : " . ($nbPass + $nbFail) . " tests | PASS : $nbPass | FAIL : $nbFail\n";
echo "========================================\n";

// Code de sortie : 0 si tout passe, 1 sinon (utile pour CI/CD)
exit($nbFail > 0 ? 1 : 0);
