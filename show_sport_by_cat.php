<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
include './includes/navbar_client.php';

session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports par Catégorie</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

<?php

if(isset($_GET['categorie_id'])){
    $categorie_id = $_GET['categorie_id'];

    $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
    $conn = $dbConn->connexion();

    $request = new Request();
    $request->setSql("SELECT id, nom FROM Sport WHERE categorie_id = :categorie_id");
    $sports = $request->getLines($conn, ["categorie_id" => $categorie_id]);

    echo "<h2 class='mb-4'>Sports dans cet catégorie:</h2>";
    echo "<div class='list-group'>"; 
    foreach ($sports as $sport) {
        echo "<a href='show_equip_by_sport.php?sport_id=" . $sport['id'] . "' class='list-group-item list-group-item-action'>" . $sport['nom'] . "</a>";
    }
    echo "</div>";

    $dbConn->deconnexion();
}
else{
    echo "<div class='alert alert-warning'>Pas de catégorie sélectionnée.</div>";
}

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
