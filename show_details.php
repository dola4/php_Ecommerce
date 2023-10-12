<?php 
include './includes/navbar_client.php';
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'équipement</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Détails de l'équipement :</h2>
    <?php

if (isset($_GET['equipement_id'])) {
    $equipement_id = $_GET['equipement_id'];

    $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
    $conn = $dbConn->connexion();

    $request = new Request();
    $request->setSql("
    SELECT 
        Equipement.id AS equip_id, 
        Equipement.nom AS equip_name, 
        Equipement.image AS equip_image, 
        Equipement.description AS equip_description,
        Equipement.prix AS equip_price, 
        Equipement.stock AS equip_stock, 
        sport.nom AS sport_name, 
        categorie.categorie AS category_name
    FROM 
        Equipement
    JOIN 
        sport ON Equipement.sport_id = sport.id
    JOIN 
        categorie ON sport.categorie_id = categorie.id
    WHERE 
        Equipement.id = :equipement_id
    ");
    $equipements = $request->getLines($conn, ["equipement_id" => $equipement_id]);


    if (count($equipements) > 0) {
        $equipement = $equipements[0]; 
        echo "<div class='card' style='width: 18rem;'>";
        echo "<img src='." . $equipement['equip_image'] . "' class='card-img-top' alt='" . $equipement['equip_name'] . "'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . $equipement['equip_name'] . "</h5>";
        echo "<p class='card-text'>" . $equipement['equip_description'] . "</p>";
        echo "<p><strong>Prix : </strong> " . $equipement['equip_price'] . "$</p>";
        echo "<p><strong>Stock : </strong> " . $equipement['equip_stock'] . "</p>";
        echo "<form action='add_to_cart.php' method='post'>";
        echo "<input type='hidden' name='equipement_id' value='" . $equipement['equip_id'] . "'>";
        echo "<div class='form-group'>";
        echo "<label for='quantite'>Quantité :</label>";
        echo "<input type='number' name='quantite' class='form-control' value='1'>";
        echo "</div>";
        echo "<input type='submit' class='btn btn-primary' value='Ajouter au panier'>";
        echo "</form>";
        echo "</div></div>";
    } else {
        echo "<div class='alert alert-danger'>Aucun équipement trouvé avec cet ID.</div>";
    }

    $dbConn->deconnexion();
} else {
    echo "<div class='alert alert-warning'>Aucun équipement sélectionné.</div>";
}
?>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
<?php
    $dbConn->deconnexion();
?>
