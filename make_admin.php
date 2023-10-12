<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';


session_start();
$user_id = $_SESSION['user']->getUser_id();



if (!empty($_POST)) {
$request = new Request();

    $dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
    $conn = $dbConn->connexion();


    $request->setSql("INSERT INTO Admin (num_employe, is_admin, user_id) VALUES
(:num_employe, :is_admin, :user_id)");
    $params = [
        'num_employe' => $_POST['num_employe'],
        'is_admin' => true,
        'user_id' => $user_id
    ];
    $request->getLines($conn, $params);

$dbConn->deconnexion();
}
?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<form action="make_admin.php" method="post">
<div class="form-group">
<label for="num_employe">Numéro d'employé</label>
<input type="text" class="form-control" name="num_employe" id="num_employe" required>
</div>
<div class="form-group">
<input type="submit" class="form-control" value="Submit">
</form>
<a href="admin_dashboard.php">Retour au tableau de bord de l'administrateur</a>

</body>
</html>