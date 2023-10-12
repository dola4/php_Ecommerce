<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
session_start( );

if(!empty($_POST)){   
    $photo_bdd= ""; 
    if(!empty($_FILES['image']['name']))
    {   
        $nom_photo = $_FILES['image']['name'];
        
        $photo_bdd = "/media/imagesEquipements/" . $nom_photo;    

        $photo_dossier="C:/xampp/htdocs/MatChaV1.1/media/imagesEquipements/".$nom_photo;
        copy($_FILES['image']['tmp_name'],$photo_dossier);
    }

    // Utilisez la classe DatabaseConnexion pour obtenir une connexion.
    $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
    $conn = $dbConn->connexion();

    $request = new Request();
    $request->setSql("INSERT INTO Equipement (nom, description, prix, image, categorie_id, sport_id, stock) 
    VALUES (:nom, :description, :prix, :image, :categorie_id, :sport_id, :stock)");
    $params = [
        'nom' => $_POST['nom'], 
        'description' => $_POST['description'],
        'prix' => $_POST['prix'],
        'image' => $photo_bdd,
        'categorie_id' => $_POST['categorie_id'],
        'sport_id' => $_POST['sport_id'],
        'stock' => $_POST['stock']
    ];
    $request->getLines($conn, $params);


    // Fermer la connexion.
    $dbConn->deconnexion();
    // Rediriger vers createEquipement.php
    header('Location: createEquipement.php');

}
?>
