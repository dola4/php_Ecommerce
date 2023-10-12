<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

// i want to insert a new categorie in the database

session_start();

if (!empty($_POST)) {
    $photo_bdd = "";
    if (!empty($_FILES['image']['name'])) {
        $nom_photo = $_FILES['image']['name'];
        $photo_bdd = "/media/imagesCategories/" . $nom_photo;
        $photo_dossier = "C:/xampp/htdocs/MatChaV1.1/media/imagesCategories/" . $nom_photo;
        copy($_FILES['image']['tmp_name'], $photo_dossier);
    }

    // Utilisez la classe DatabaseConnexion pour obtenir une connexion.
    $dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
    $conn = $dbConn->connexion();

    // Utilisez la classe Request pour préparer et exécuter la requête.
    $request = new Request();
    $request->setSql("INSERT INTO Categorie (categorie, image) VALUES (:categorie, :image)");
    $params = [
        'categorie' => $_POST['categorie'],
        'image' => $photo_bdd
    ];
    $request->getLines($conn, $params);


    // Fermer la connexion.
    $dbConn->deconnexion();

    // Rediriger vers createCategorie.php
    header('Location: createCategorie.php');
}
