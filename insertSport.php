<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
session_start();

if(!empty($_POST)){   
    $photo_bdd= ""; 
    if(!empty($_FILES['image']['name']))
    {   
        $nom_photo = $_FILES['image']['name'];
        $photo_bdd = "/media/imagesSports/" . $nom_photo;    
        $photo_dossier="C:/xampp/htdocs/MatChaV1.1/media/imagesSports/".$nom_photo;
        copy($_FILES['image']['tmp_name'],$photo_dossier);
    }

    // Utilisez la classe DatabaseConnexion pour obtenir une connexion.
    $dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
    $conn = $dbConn->connexion();


    // Utiliser la classe Request pour préparer et exécuter la requête.
    $request = new Request();
    $request->setSql("INSERT INTO Sport (nom, description, image, categorie_id) VALUES (:nom, :description, :image, :categorie_id)");
    $params = [
        'nom' => $_POST['nom'], 
        'description' => $_POST['description'],
        'image' => $photo_bdd,
        'categorie_id' => $_POST['categorie_id']
    ];
    $request->getLines($conn, $params);


    // Fermer la connexion.
    $dbConn->deconnexion();
    // Rediriger vers createCategorie.php
    header('Location: createSport.php');
}
?>
