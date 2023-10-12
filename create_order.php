<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
include './includes/navbar_client.php';

session_start();

$dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
$conn = $dbConn->connexion();

$request = new Request();

$request -> setSql("SELECT id FROM Adresse WHERE user_id = :user_id");
$adresse_id = $request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id()])[0]['id'];

$request->setSql("INSERT INTO Commande (user_id, adresse_id, date_commande) VALUES (:user_id, :adresse_id, NOW())");
$request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id(), "adresse_id" => $adresse_id]);
$last_inserted_id = $conn->lastInsertId();

echo "last inserted id <pre>";
print_r($last_inserted_id);
echo "</pre>";

// Récupérez les articles du panier
$request->setSql("SELECT e.id AS equipement_id, e.nom, e.prix, pe.quantite FROM PanierEquipement pe JOIN Equipement e ON pe.equipement_id = e.id WHERE pe.panier_id = :panier_id");
$articles = $request->getLines($conn, ["panier_id" => $_SESSION['user']->getUser_id()]);

echo "request <pre>";
print_r($articles);
echo "</pre>";

foreach ($articles as $article) {
    // Insérez les détails dans CommandeEquipement
    $request->setSql("INSERT INTO CommandeEquipement (commande_id, equipement_id, equipement_prix, quantite) VALUES (:commande_id, :equipement_id, :equipement_prix, :quantite)");
    $request->getLines($conn, [
        "commande_id" => $last_inserted_id,
        "equipement_id" => $article['equipement_id'],
        "equipement_prix" => $article['prix'],
        "quantite" => $article['quantite']
    ]);
    
    // Mettez à jour le stock de Equipement
    $request->setSql("UPDATE Equipement SET stock = stock - :quantite WHERE id = :equipement_id");
    $request->getLines($conn, [
        "equipement_id" => $article['equipement_id'],
        "quantite" => $article['quantite']
    ]);
}

echo "Commande créée avec succès!";
$dbConn->deconnexion();
?>
