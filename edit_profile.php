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

$isClient = false;
$isAdmin = false;

$request->setSql("SELECT * FROM User WHERE id = :user_id");
$userData = $request->getLines($conn, ["user_id" => $user_id], true);

$request->setSql("SELECT * FROM Client WHERE user_id = :user_id");
$clientData = $request->getLines($conn, ["user_id" => $user_id], true);

$request->setSql("SELECT * FROM Admin WHERE user_id = :user_id");
$adminData = $request->getLines($conn, ["user_id" => $user_id], true);

$request->setSql("SELECT * FROM adresse WHERE user_id = :user_id");
$adresseData = $request->getLines($conn, ["user_id" => $user_id], true);



if ($clientData) {
    $isClient = true;
} else {
    $isAdmin = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $age = $_POST['age'];
    if ($isClient) {
        $telephone = $_POST['telephone'];
    } else if ($isAdmin) {
        $num_employe = $_POST['num_employe'];
    }
    $email = $_POST['email'];
    $pw = $_POST['pw'];  // Assurez-vous de hasher le mot de passe avant de le stocker
    $numero = $_POST['numero'];
    $rue = $_POST['rue'];
    $appartement = $_POST['appartement'];
    $ville = $_POST['ville'];
    $code_postal = $_POST['code_postal'];
    $province = $_POST['province'];
    $pays = $_POST['pays'];

    // Mise à jour de la table User
    $request->setSql("UPDATE User SET email = :email, prenom = :prenom, nom = :nom, age = :age, pw = :pw WHERE id = :user_id");
    $request->getLines($conn, [
        "prenom" => $prenom,
        "nom" => $nom,
        "age" => $age,
        "email" => $email,
        "pw" => $pw,
        "user_id" => $user_id
    ]);

    if ($isClient) {
        $request->setSql("UPDATE Client SET telephone = :telephone WHERE user_id = :user_id");
        $request->getLines($conn, ["telephone" => $telephone, "user_id" => $user_id]);
    } else if ($isAdmin) {
        $request->setSql("UPDATE Admin SET num_employe = :num_employe WHERE user_id = :user_id");
        $request->getLines($conn, ["num_employe" => $num_employe, "user_id" => $user_id]);
    }

    // Mise à jour de la table Adresse
    $request->setSql("UPDATE Adresse SET numero = :numero, rue = :rue, apartement = :appartement, ville = :ville, codePostal = :code_postal, province = :province, pays = :pays WHERE user_id = :user_id");
    $request->getLines($conn, [
        "numero" => $numero,
        "rue" => $rue,
        "appartement" => $appartement,
        "ville" => $ville,
        "code_postal" => $code_postal,
        "province" => $province,
        "pays" => $pays,
        "user_id" => $user_id
    ]);
    header('Location: boutique.php');
}



$dbConnection->deconnexion();
?>

<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h1 class="mb-4">Modifier le profil de <?= $userData['prenom'] ?></h1>

        <form action="edit_profile.php" method="post">
            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= $userData['prenom'] ?>">
            </div>
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?= $userData['nom'] ?>">
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="age" name="age" value="<?= $userData['age'] ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $userData['email'] ?>">
            </div>
            <div class="form-group">
                <label for="pw">Mot de passe:</label>
                <input type="password" class="form-control" id="pw" name="pw" value="<?= $userData['pw'] ?>">
            </div>
            <?php
            if ($isClient) { ?>
            <div class="form-group">
                <label for="telephone">Téléphone:</label>
                <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= $clientData['telephone'] ?>">
            </div>
            <?php } else if ($isAdmin) { ?>
            <div class="form-group">
                <label for="num_employe">Numéro d'employé:</label>
                <input type="text" class="form-control" id="num_employe" name="num_employe" value="<?= $adminData['num_employe'] ?>">
            </div>
            <?php } ?>

            <h4>Adresse : </h4>

            <div class="form-group">
                <label for="numero">Numéro:</label>
                <input type="text" class="form-control" id="numero" name="numero" value="<?= $adresseData['numero'] ?>">
            </div>
            <div class="form-group">
                <label for="rue">Rue:</label>
                <input type="text" class="form-control" id="rue" name="rue" value="<?= $adresseData['rue'] ?>">
            </div>
            <div class="form-group">
                <label for="appartement">Appartement:</label>
                <input type="text" class="form-control" id="appartement" name="appartement" value="<?= $adresseData['apartement'] ?>">
            </div>
            <div class="form-group">
                <label for="ville">Ville:</label>
                <input type="text" class="form-control" id="ville" name="ville" value="<?= $adresseData['ville'] ?>">
            </div>
            <div class="form-group">
                <label for="code_postal">Code postal:</label>
                <input type="text" class="form-control" id="code_postal" name="code_postal" value="<?= $adresseData['codePostal'] ?>">
            </div>
            <div class="form-group">
                <label for="province">Province:</label>
                <input type="text" class="form-control" id="province" name="province" value="<?= $adresseData['province'] ?>">
            </div>
            <div class="form-group">
                <label for="pays">Pays:</label>
                <input type="text" class="form-control" id="pays" name="pays" value="<?= $adresseData['pays'] ?>">
            </div>





            <!-- ... Ajoutez des champs pour l'adresse ... -->

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</body>

</html>