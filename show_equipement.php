<?php
include './includes/navbar_client.php';
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Équipements</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

<?php
$dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
$conn = $dbConn->connexion();

$request = new Request();
$request->setSql("SELECT id, nom, image, prix, stock FROM Equipement");
$equipements = $request->getLines($conn);

echo "<h2 class='mb-4'>Tous les Équipements de tous les sports:</h2>";

if (count($equipements) > 0) {
    echo "<div class='row'>";
    foreach ($equipements as $equipement) {
        echo "<div class='col-md-4 mb-4'>";
        echo "<div class='card'>";
        // Image
        if (isset($equipement['image']) && !empty($equipement['image'])) {
            echo "<a href='show_details.php?equipement_id=" . $equipement['id'] . "'><img src='." . $equipement['image'] . "' alt='" . $equipement['nom'] . "' class='card-img-top'></a>";
        }
        echo "<div class='card-body'>";
        echo "<a href='show_details.php?equipement_id=" . $equipement['id'] . "' class='card-title'>" . $equipement['nom'] ."</a>";
        echo "<p class='card-text'><strong>Prix:</strong> " . $equipement['prix'] . "$</p>";
        echo "<form action='add_to_cart_session.php' method='post'>";
        echo "<input type='hidden' name='equipement_id' value='" . $equipement['id'] . "'>";
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
        echo "<input type='hidden' name='user_id' value='" . $user_id . "'>";
        echo "<input type='submit' class='btn btn-primary' value='Ajouter au panier'>";
        echo "</form>";
        echo "</div>";  // End card-body
        echo "</div>";  // End card
        echo "</div>";  // End col-md-4
    }
    echo "</div>";  // End row
} else {
    echo "<div class='alert alert-warning'>Aucun équipement trouvé.</div>";
}

$dbConn->deconnexion();

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>