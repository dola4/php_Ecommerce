<?php
include './includes/navbar_admin.php';  
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

session_start();

$order_id = $_GET['id'];
$dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
$conn = $dbConn->connexion();

$request = new Request();
$request->setSql("DELETE FROM Commande WHERE id = ?");
$request->getLines($conn, [$order_id], true);

echo "Commande supprimée avec succès.";

?>
<br>
<a href="show_orders.php">Retour aux commandes</a>
