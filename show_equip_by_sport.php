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
    <title>Équipements par Sport</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Équipements pour ce sport:</h2>
    <?php
    if (isset($_GET['sport_id'])) {
        $sport_id = $_GET['sport_id'];

    $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
    $conn = $dbConn->connexion();

    $request = new Request();
    $request->setSql("SELECT id, nom, prix, image, stock FROM Equipement WHERE sport_id = :sport_id");
    $equipements = $request->getLines($conn, ["sport_id" => $sport_id]);

    if (count($equipements) > 0) {
        foreach ($equipements as $equipement) {
            echo "<div class='card mb-3' style='max-width: 540px;'>";
            echo "<div class='row no-gutters'>";
            echo "<div class='col-md-4'>";
            if (isset($equipement['image']) && !empty($equipement['image'])) {
                echo "<a href='show_details.php?equipement_id=" . $equipement['id'] . "'><img src='." . $equipement['image'] . "' class='card-img' alt='" . $equipement['nom'] . "'></a>";
            }
            echo "</div>";
            echo "<div class='col-md-8'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'><a href='show_details.php?equipement_id=" . $equipement['id'] . "'>" . $equipement['nom'] ."</a></h5>";
            echo "<p class='card-text'><strong>Prix:</strong> " . $equipement['prix'] . "$</p>";
            echo "<form action='add_to_cart_session.php' method='post'>";
            echo "<input type='hidden' name='equipement_id' value='" . $equipement['id'] . "'>";
            echo "<input type='submit' class='btn btn-primary' value='Ajouter au panier'>";
            echo "</form>";
            echo "</div></div></div></div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Aucun équipement trouvé pour ce sport.</div>";
    }

    $dbConn->deconnexion();
} else {
    echo "<div class='alert alert-warning'>Pas de sport sélectionné.</div>";
}
?>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>