<?php
include './includes/navbar_admin.php';  // Ensure you have a navbar suited for admins
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

session_start();

// Admin authentication check
/* if(!$_SESSION['user'] || $_SESSION['user']->getRole() != 'admin') {
    header('Location: login.php');
    exit;
} */

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">
    <?php
    $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
    $conn = $dbConn->connexion();

    $request = new Request();

    $request->setSql("
SELECT 
    c.id AS commande_id, 
    c.user_id, 
    c.adresse_id, 
    c.date_commande, 
    e.nom AS equipement_nom,
    ce.equipement_prix, 
    ce.quantite,
    (ce.equipement_prix * ce.quantite) AS total_for_item,
    u.email AS user_email, 
    CONCAT(a.numero, ' ', a.rue, ', ', a.ville, ', ', a.province, ', ', a.codePostal, ', ', a.pays) AS adresse_complete
FROM 
    Commande c
JOIN 
    CommandeEquipement ce ON c.id = ce.commande_id
JOIN 
    Equipement e ON ce.equipement_id = e.id
JOIN 
    User u ON c.user_id = u.id
JOIN 
    Adresse a ON c.adresse_id = a.id;
");

    $orders = $request->getLines($conn);

    $groupedOrders = [];
    foreach ($orders as $order) {
        $orderId = $order['commande_id'];
        if (!isset($groupedOrders[$orderId])) {
            $groupedOrders[$orderId] = [
                'id' => $orderId,
                'user_id' => $order['user_id'],
                'adresse_id' => $order['adresse_id'],
                'adresse_complete' => $order['adresse_complete'],
                'date_commande' => $order['date_commande'],
                'total_for_item' => $order['total_for_item'],
                'equipements' => []
            ];
        }
        $groupedOrders[$orderId]['equipements'][] = $order['equipement_nom'] . " (" . $order['quantite'] . " à " . $order['equipement_prix'] . "$)";
    }

    $totalAllOrders = 0;
    foreach ($groupedOrders as $order) {
        $totalAllOrders += $order['total_for_item'];
    }

    echo "<h2 class='mb-4'>Commandes: <span class='float-right'>Total des commandes : " . $totalAllOrders . " $</span></h2>";

    if (count($orders) > 0) {
        echo "<table class='table table-bordered table-hover'>";
        echo "<thead class='thead-dark'>";
        echo "<tr>";
        echo "<th>ID Commande</th>";
        echo "<th>ID Utilisateur</th>";
        echo "<th>Adresse</th>"; 
        echo "<th>Date de la Commande</th>";
        echo "<th>Équipements</th>";
        echo "<th>Total pour l'item</th>";
        echo "<th>Action</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($groupedOrders as $order) {
            echo "<tr>";
            echo "<td>" . $order['id'] . "</td>";
            echo "<td>" . $order['user_id'] . "</td>";
            echo "<td>" . $order['adresse_complete'] . "</td>";
            echo "<td>" . $order['date_commande'] . "</td>";
            echo "<td>" . implode(", ", $order['equipements']) . "</td>"; // List of ordered equipment
            echo "<td>" . $order['total_for_item'] . " $</td>";
            echo "<td>";
            echo "<a href='edit_order.php?id=" . $order['id'] . "'>Éditer</a> | ";
            echo "<a href='delete_order.php?id=" . $order['id'] . "'>Supprimer</a>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "<div class='mt-3'>Total des commandes : " . $totalAllOrders . " $</div>";
    } else {
        echo "<div class='alert alert-warning'>Aucune commande trouvée.</div>";
    }

    $dbConn->deconnexion();

    ?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
