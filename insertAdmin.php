<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!empty($_POST)) {
    // Utilisez la classe DatabaseConnexion pour obtenir une connexion.
    $dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
    $conn = $dbConn->connexion();

    // Utilisez la classe Request pour préparer et exécuter la requête.
    $request = new Request();
    $request->setSql("INSERT INTO User (email, pw, age, nom, prenom) VALUES (:email, :pw, :age, :nom, :prenom)");
    $params = [
        'email' => $_POST['email'],
        'pw' => password_hash($_POST['pw'], PASSWORD_DEFAULT),
        'age' => $_POST['age'],
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom']
    ];
    $request->getLines($conn, $params);

    $last_id = $conn->lastInsertId();
    $request->setSql("INSERT INTO Admin (num_employe, is_admin, user_id) VALUES
(:num_employe, :is_admin, :user_id)");
    $params = [
        'num_employe' => $_POST['num_employe'],
        'is_admin' => true,
        'user_id' => $last_id
    ];
    $request->getLines($conn, $params);

    $request->setSql("INSERT INTO Adresse (numero, apartement, rue, ville, codePostal, province, pays, user_id) VALUES
(:numero, :apartement, :rue, :ville, :codePostal, :province, :pays, :user_id)");
    $params = [
        'numero' => $_POST['numero'],
        'apartement' => $_POST['apartement'],
        'rue' => $_POST['rue'],
        'ville' => $_POST['ville'],
        'codePostal' => $_POST['codePostal'],
        'province' => $_POST['province'],
        'pays' => $_POST['pays'],
        'user_id' => $last_id
    ];
    $request->getLines($conn, $params);
    $errorInfo = $conn->errorInfo();

    // Après avoir inséré dans la table Adresse
    $last_adresse_id = $conn->lastInsertId();

    // Maintenant, effectuons une insertion dans UserAdresse pour lier l'utilisateur et l'adresse
    $request->setSql("INSERT INTO UserAdresse (user_id, adresse_id) VALUES (:user_id, :adresse_id)");
    $params = [
        'user_id' => $last_id,
        'adresse_id' => $last_adresse_id
    ];
    $request->getLines($conn, $params);
    $errorInfo = $conn->errorInfo();
    if (isset($errorInfo[2])) {
        echo $errorInfo[2];
    }
}

// Fermer la connexion.
$dbConn->deconnexion();
