<?php
// Modele de base : fournit la connexion PDO a tous les modeles enfants
class Model {
    protected PDO $db;

    public function __construct() {
        $this->db = getDB();
    }
}
