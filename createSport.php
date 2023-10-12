<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="function.js"></script>
</head>

<body>
  <?php include './includes/navbar_admin.php'; ?>

  <div class="container mt-4">
    <h2>Créer un nouveau sport</h2>
    <form action="insertSport.php" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="nom">Nom du sport:</label>
        <input type="text" class="form-control" name="nom" id="nom">
      </div>
      <div class="form-group">
        <label for="description">Description:</label>
        <textarea class="form-control" name="description" id="description"></textarea>
      </div>
      <div class="form-group">
        <label for="image">Image:</label>
        <input type="file" class="form-control" name="image" id="image">
      </div>
      <?php
      include './includes/chargement.php';
      include './app/config/database.php';
      include './asset/bd/env.php';

      // Utilisez la classe DatabaseConnexion pour obtenir une connexion.
      $dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
      $conn = $dbConn->connexion();

      // Utiliser la classe Request pour préparer et exécuter la requête.
      $request = new Request();
      $request->setSql("SELECT id, categorie FROM Categorie");
      ?>
      <div class="form-group">
        <label for="categorie">Categorie:</label>
        <select name="categorie_id" class="form-control" id="categorie">
          <?php
          try {
            $categories = $request->getLines($conn);
            foreach ($categories as $category) {
              echo "<option value='" . $category['id'] . "'>" . $category['categorie'] . "</option>";
            }
          } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
          }
          ?>
        </select>
      </div>
      <?php
      // Fermer la connexion.
      $dbConn->deconnexion();
      ?>
      </select><br>
      <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    <?php include './includes/footer_admin.php'; ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>