<?php
include './includes/navbar_client.php';
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

session_start();

$dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
$conn = $dbConn->connexion();

$request = new Request();

$action = $_POST['action'];
$equipement_id = $_POST['equipement_id'];

// Récupérez l'ID du panier de l'utilisateur
$request->setSql("SELECT id FROM Panier WHERE user_id = :user_id");
$panier = $request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id()], true);
$panier_id = $panier['id'];

switch ($action) {
    case 'update':
        $quantite = $_POST['quantite'];
        $request->setSql("UPDATE PanierEquipement SET quantite = :quantite WHERE panier_id = :panier_id AND equipement_id = :equipement_id");
        $request->getLines($conn, ["panier_id" => $panier_id, "equipement_id" => $equipement_id, "quantite" => $quantite]);
        break;

    case 'delete':
        $request->setSql("DELETE FROM PanierEquipement WHERE panier_id = :panier_id AND equipement_id = :equipement_id");
        $request->getLines($conn, ["panier_id" => $panier_id, "equipement_id" => $equipement_id]);
        break;

    default:
        echo "Action non valide.";
        exit;
}

// Redirection vers la page du panier après avoir effectué les modifications
header("Location: show_cart.php"); 
exit;
?>
