<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <!-- ... autres éléments de l'en-tête ... -->
    <script src="function.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="function.js"></script>
</head>

<body>

    <?php include './includes/navbar_admin.php'; ?>
    <div class="container mt-4">
        <h2>Créer un nouvel équipement</h2>
        <form action="insertEquipement.php" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="nom">Nom de l'équipement:</label>
                <input type="text" class="form-control" name="nom" id="nom">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" id="description"></textarea>
            </div>
            <div class="form-group">
                <label for="prix">Prix:</label>
                <input type="number" step="0.01" class="form-control" name="prix" id="prix">
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control" name="image" id="image">
            </div>
            <!-- Categorie: <select name="categorie_id"> -->
            <?php
            include './includes/chargement.php';
            include './app/config/database.php';
            include './asset/bd/env.php';

            /* // Utilisez la classe DatabaseConnexion pour obtenir une connexion.
    $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
    $conn = $dbConn->connexion();

    // Utiliser la classe Request pour préparer et exécuter la requête.
    $request = new Request();
    $request->setSql("SELECT id, categorie FROM Categorie");

    try {
        $categories = $request->getLines($conn);
        foreach ($categories as $category) {
            echo "<option value='" . $category['id'] . "'>" . $category['categorie'] . "</option>";
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Fermer la connexion.
    $dbConn->deconnexion(); */
            ?>
            <!-- </select><br> -->
            <div class="form-group">
                <label for="sport_id">Sport:</label>
                <select name="sport_id" class="form-control" id="sport_id">
                    <?php

                    // Utilisez la classe DatabaseConnexion pour obtenir une connexion.
                    $dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
                    $conn = $dbConn->connexion();

                    // Utiliser la classe Request pour préparer et exécuter la requête.
                    $request = new Request();
                    $request->setSql("SELECT id, nom FROM Sport");

                    try {
                        $sports = $request->getLines($conn);
                        var_dump($sports);
                        foreach ($sports as $sport) {
                            echo "<option value='" . $sport['id'] . "'>" . $sport['nom'] . "</option>";
                        }
                    } catch (Exception $e) {
                        echo "Erreur : " . $e->getMessage();
                    }

                    // Fermer la connexion.
                    $dbConn->deconnexion();
                    ?>
                </select><br>
                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" class="form-control" step="1" name="stock" id="stock">
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </div>       
        </form>
        <?php include './includes/footer_admin.php'; ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>