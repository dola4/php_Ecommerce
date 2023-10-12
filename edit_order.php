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
$request->setSql("SELECT * FROM Commande WHERE id = ?");
$order = $request->getLines($conn, [$order_id], true);

// Vérifie si la commande existe
if (!$order) {
    echo "Commande non trouvée.";
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit command</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<div class="container mt-4">
    <form action="edit_order.php" method="post" class="form-group">
        
        <div class="mb-3">
            <label for="user_id" class="form-label">ID Utilisateur:</label>
            <input type="text" name="user_id" class="form-control" value="<?php echo $order['user_id']; ?>" required>
        </div>

        <div class="mb-3">
            <label for="adresse_id" class="form-label">Adresse ID:</label>
            <input type="text" name="adresse_id" class="form-control" value="<?php echo $order['adresse_id']; ?>" required>
        </div>

        <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
        <input type="submit" value="Mettre à jour" class="btn btn-primary">
    </form>

    <a href="show_orders.php" class="btn btn-secondary mt-2">Retour aux commandes</a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
