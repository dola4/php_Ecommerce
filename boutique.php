<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
include './includes/navbar_client.php';

session_start();

$dbConnection = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
$conn = $dbConnection->connexion();

// Récupération des données de l'utilisateur depuis la base de données
$user_id = $_SESSION['user']->getUser_id();
$request = new Request();
// Récupération des données de l'utilisateur en tant que client
$request->setSql("SELECT * FROM Client WHERE user_id = :user_id");
$clientData = $request->getLines($conn, ["user_id" => $user_id], true);
// Si l'utilisateur n'est pas trouvé en tant que client, vérifier s'il est un admin
if (!$clientData) {
    $request->setSql("SELECT * FROM Admin WHERE user_id = :user_id");
    $adminData = $request->getLines($conn, ["user_id" => $user_id], true);
    
    $request->setSql("SELECT * FROM adresse WHERE user_id = :user_id");
    $adresseData = $request->getLines($conn, ["user_id" => $adminData['user_id']], true);

    $request->setSql("SELECT * FROM User WHERE id = :user_id");
    $userData = $request->getLines($conn, ["user_id" => $adminData['user_id']], true);

} else {
    $request->setSql("SELECT * FROM adresse WHERE user_id = :user_id");
    $adresseData = $request->getLines($conn, ["user_id" => $clientData['user_id']], true);

    $request->setSql("SELECT * FROM User WHERE id = :user_id");
    $userData = $request->getLines($conn, ["user_id" => $clientData['user_id']], true);
}



$dbConnection->deconnexion();

?>

<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    
<div class="container mt-5">
    <h1 class="mb-4">Profil de <?= $userData['prenom'] ?></h1>

    <div class="card">
        <div class="card-header">
            Informations personnelles
        </div>
        <div class="card-body">
            <p><strong>Nom:</strong> <?= $userData['prenom'] . " " . $userData['nom'] ?></p>
            <?php if ($clientData): ?>
                <p><strong>Telephone:</strong> <?= $clientData['telephone'] ?></p>
            <?php elseif ($adminData): ?>
                <p><strong>Numero d'employe:</strong> <?= $adminData['num_employe'] ?></p>
            <?php endif; ?>
            <p><strong>Age:</strong> <?= $userData['age'] ?> ans</p>
            <p><strong>Email:</strong> <?= $userData['email'] ?></p>
            <h4>Adresse:</h4>
            <p><?= $adresseData['numero'] ?> <?= $adresseData['rue'] ?></p>
            <p><strong>app: </strong><?= $adresseData['apartement'] ?>, <?= $adresseData['codePostal'] ?></p>
            <p> <?= $adresseData['ville'] ?>, <?=$adresseData['province'] ?> <?=$adresseData['pays'] ?> </p>

            
            
            <!-- Ajoutez d'autres champs ici -->
        </div>
    </div>

    <!-- Vous pouvez ajouter d'autres sections comme l'historique des commandes, les paramètres, etc. -->

    <a href="edit_profile.php" class="btn btn-primary mt-3">Modifier le profil</a>
</div>
</body>

</html>