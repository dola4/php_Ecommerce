<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include './includes/navbar_admin.php'; ?>
<form action="insertCategorie.php" method="post" enctype="multipart/form-data">
<div class="form-group">
  <label for="categorie">Categorie:</label>
  <input type="text" class="form-control" name="categorie">
</div>
<div class="form-group">
  <label for="image">Image:</label>
  <input type="file" name="image"><br>
  <input type="submit" class="form-control" value="Submit">
</form>
<?php include './includes/footer_admin.php'; ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
