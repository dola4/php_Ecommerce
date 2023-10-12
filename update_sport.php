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

// Obtenez l'ID du sport à partir de l'URL ou de la méthode POST.
$sport_id = $_GET['id'] ?? null;

$dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
$conn = $dbConn->connexion();

// Récupérer les données du sport en question
$request = new Request();
$request->setSql("SELECT * FROM Sport WHERE id = :id");
$sport = $request->getLines($conn, ['id' => $sport_id])[0] ?? null;

if ($_POST) {
    $photo_bdd = $sport['image'];

    if (!empty($_FILES['image']['name'])) {
        // Supprimer l'ancienne image s'il y en a une
        if ($photo_bdd) {
            unlink("C:/xampp/htdocs/MatChaV1.1" . $photo_bdd);
        }

        $nom_photo = $_FILES['image']['name'];
        $photo_bdd = "/media/imagesSports/" . $nom_photo;
        $photo_dossier = "C:/xampp/htdocs/MatChaV1.1/media/imagesSports/" . $nom_photo;
        copy($_FILES['image']['tmp_name'], $photo_dossier);
    }

    $request->setSql("UPDATE Sport SET nom = :nom, description = :description, image = :image, categorie_id = :categorie_id WHERE id = :id");
    $params = [
        'id' => $sport_id,
        'nom' => $_POST['nom'],
        'description' => $_POST['description'],
        'image' => $photo_bdd,
        'categorie_id' => $_POST['categorie_id']
    ];
    $request->getLines($conn, $params);

    $dbConn->deconnexion();

    header('Location: admin_dashboard.php');
    exit;
}

?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Equipement</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">

        <h1>Edit Sport</h1>
        <form action="update_sport.php?id=<?php echo $sport_id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $sport['nom']; ?>">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"><?php echo $sport['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image actuelle:</label>
                <img src=".<?php echo $sport['image']; ?>" alt="Sport Image" width="100">
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control" id="image" name="image">
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
            <input type="submit" class="btn btn-primary" value="Mettre à jour">
        </form>
        <a href="admin_dashboard.php">Back to Admin Dashboard</a>

</body>

</html>