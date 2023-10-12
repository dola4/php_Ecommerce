<?php
session_start();

include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
include './includes/navbar_admin.php';

// Check if user is logged in and is an admin
/* if (!isset($_SESSION['user']) || $_SESSION['user_type'] != 'admin') {
    header('Location: login.php');
    exit;
} */

$dbConnection = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
$conn = $dbConnection->connexion();

$request = new Request();

$id = $_GET['id'];
$request->setSql('SELECT * FROM Categorie WHERE id = :id');
$category = $request->getLines($conn, ['id' => $id]);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $category_name = $_POST['category_name'];

    $photo_bdd = $category['image'];  // keep the old image by default
    if (!empty($_FILES['image']['name'])) {
        $nom_photo = $_FILES['image']['name'];
        $photo_bdd = "/media/imagesCategories/" . $nom_photo;
        $photo_dossier = "C:/xampp/htdocs/MatChaV1.1/media/imagesCategories/" . $nom_photo;
        copy($_FILES['image']['tmp_name'], $photo_dossier);
    }
    
    // Update category
    $request->setSql('UPDATE Categorie SET categorie = :categorie, image = :image WHERE id = :id');
    $params = [
        'categorie' => $category_name,
        'image' => $photo_bdd,
        'id' => $id
    ];
    $request->getLines($conn, $params);
    
    $dbConn->deconnexion();
    
    header('Location: admin_dashboard.php');
    exit;
}



$id = $_GET['id'];
$request->setSql('SELECT * FROM Categorie WHERE id = ?');
$category = $request->getLines($conn, [$id]);

$dbConnection->deconnexion();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1>Edit Category</h1>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <img src="<?php echo "." . $category[0]['image']; ?>" alt="Category Image" width="100" class="img-thumbnail mb-3">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
        </div>
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <input type="text" id="category_name" name="category_name" value="<?php echo $category[0]['categorie']; ?>" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="image">Choose new image (optional):</label>
            <input type="file" id="image" name="image" class="form-control-file">
        </div>
        <input type="submit" value="Update" class="btn btn-primary">
    </form>
    <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Admin Dashboard</a>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>