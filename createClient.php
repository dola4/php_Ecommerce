<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <?php include './includes/navbar_admin.php'; ?>
  <form action="insertClient.php" method="post">
    <div class="form-group">
      <label for="prenom">Prénom:</label>
      <input type="text" class="form-control" name="prenom" id="prenom">
    </div>
    <div class="form-group">
      <label for="nom">Nom:</label>
      <input type="text" class="form-control" name="nom" id="nom">
    </div>
    <div class="form-group">
      <label for="age">Age:</label>
      <input type="number" class="form-control" name="age" id="age">
    </div>
    <div class="form-group">
      <label for="telephone">Téléphone:</label>
      <input type="text" class="form-control" name="telephone" id="telephone">
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" name="email" id="email">
    </div>
    <div class="form-group">
      <label for="pw">Mot de passe:</label>
      <input type="password" class="form-control" name="pw" id="pw">
    </div>
    <div class="form-group">
      <label for="numero">Numero de la porte:</label>
      <input type="number" class="form-control" name="numero" id="numero">
    </div>
    <div class="form-group">
      <label for="apartement">Appartement:</label>
      <input type="text" class="form-control" name="apartement" id="apartement">
    </div>
    <div class="form-group">
      <label for="rue">Rue:</label>
      <input type="text" class="form-control" name="rue" id="rue">
    </div>
    <div class="form-group">
      <label for="ville">Ville:</label>
      <input type="text" class="form-control" name="ville" id="ville">
    </div>
    <div class="form-group">
      <label for="codePostal">Code Postal:</label>
      <input type="text" class="form-control" name="codePostal" id="codePostal">
    </div>
    <div class="form-group">
      <label for="province">Province:</label>
      <input type="text" class="form-control" name="province" id="province">
    </div>
    <div class="form-group">
      <label for="pays">Pays:</label>
      <input type="text" class="form-control" name="pays" id="pays">
    </div>
    <input type="submit" class="form-control" value="Submit">
  </form>

  <?php include './includes/footer_admin.php'; ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>