<!DOCTYPE html>
<html>
<body>

<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
include './includes/navbar_admin.php';

session_start();

// Check if user is logged in and is an admin
/* if (!isset($_SESSION['user']) || $_SESSION['user_type'] != 'admin') {
    header('Location: login.php');
    exit;
} */

$equipement_id = $_GET['id'] ?? null;

$dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
$conn = $dbConn->connexion();

$request = new Request();
$request->setSql("SELECT * FROM Equipement WHERE id = :id");
$equipement = $request->getLines($conn, ['id' => $equipement_id])[0] ?? null;

if ($_POST) {
    $photo_bdd = $equipement['image']; 

    if (!empty($_FILES['image']['name'])) {
        if ($photo_bdd) {
            unlink("C:/xampp/htdocs/MatChaV1.1" . $photo_bdd);
        }

        $nom_photo = $_FILES['image']['name'];
        $photo_bdd = "/media/imagesEquipements/" . $nom_photo;    
        $photo_dossier = "C:/xampp/htdocs/MatChaV1.1/media/imagesEquipements/" . $nom_photo;
        copy($_FILES['image']['tmp_name'], $photo_dossier);
    }

    $request->setSql("UPDATE Equipement SET nom = :nom, description = :description, prix = :prix, image = :image, categorie_id = :categorie_id, sport_id = :sport_id, stock = :stock WHERE id = :id");
    $params = [
        'id' => $equipement_id,
        'nom' => $_POST['nom'], 
        'description' => $_POST['description'],
        'prix' => $_POST['prix'],
        'image' => $photo_bdd,
        'categorie_id' => $_POST['categorie_id'],
        'sport_id' => $_POST['sport_id'],
        'stock' => $_POST['stock']
    ];
    $request->getLines($conn, $params);

    $dbConn->deconnexion();
    
    header('Location: admin_dashboard.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipement</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <h1>Edit Equipement</h1>

<form action="update_equipement.php?id=<?php echo $equipement_id; ?>" method="post" enctype="multipart/form-data">
<div class="form-group">
    <label for="nom">Nom de l'équipement:</label>
    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $equipement['nom']; ?>">
</div>
<div class="form-group">
    <label for="description">Description:</label>
    <textarea class="form-control" id="description" name="description"><?php echo $equipement['description']; ?></textarea>
</div>
<div class="form-group">
    <label for="prix">Prix:</label>
    <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="<?php echo $equipement['prix']; ?>">
</div>
<div class="form-group">
    <label for="image">Image actuelle:</label>
    <img src=".<?php echo $equipement['image']; ?>" alt="Equipement Image" width="100">
</div>
<div class="form-group">
    <label for="image">Image:</label>
    <input type="file" class="form-control" id="image" name="image">
</div>
<div class="form-group">
            <label for="sport_id">Sport:</label>
            <select class="form-control" id="sport_id" name="sport_id">
                <?php
                $request->setSql("SELECT id, nom FROM Sport");
                $sports = $request->getLines($conn);
                foreach ($sports as $sport) {
                    $selected = $equipement['sport_id'] == $sport['id'] ? 'selected' : '';
                    echo "<option value='" . $sport['id'] . "' " . $selected . ">" . $sport['nom'] . "</option>";
                }
                ?>
            </select>
        </div>
    <div class="form-group">
            <label for="categorie_id">Categorie:</label>
            <select class="form-control" id="categorie_id" name="categorie_id">
                <?php
                $request->setSql("SELECT id, categorie FROM Categorie");
                $categories = $request->getLines($conn);
                foreach ($categories as $category) {
                    $selected = $equipement['categorie_id'] == $category['id'] ? 'selected' : '';
                    echo "<option value='" . $category['id'] . "' " . $selected . ">" . $category['categorie'] . "</option>";
                }
                ?>
            </select>
        </div>
<div class="form-group">
    <label for="stock">Stock:</label>
    <input type="number" step="1" class="form-control" id="stock" name="stock" value="<?php echo $equipement['stock']; ?>">
</div>
    <input type="submit" class="btn btn-primary" value="Mettre à jour">
</form>
<a href="admin_dashboard.php">Back to Admin Dashboard</a>
</div>
</body>
</html>



