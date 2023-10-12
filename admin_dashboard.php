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

// Fetch all categories, sports, and equipment
$request->setSql('SELECT * FROM Categorie');
$categories = $request->getLines($conn);

$request->setSql('SELECT * FROM Sport');
$sports = $request->getLines($conn);

/* $request->setSql('SELECT * FROM Equipment');
$equipment = $request->getLines($conn); */

$dbConnection->deconnexion();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- CSS pour Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<h1>Admin Dashboard</h1>
<section class="mt-5">
    <h2>Categories</h2>
    <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['categorie']; ?></td>
                <td>
                    <a href="update_categorie.php?id=<?php echo $category['id']; ?>">Edit</a> | 
                    <a href="delete_categorie.php?id=<?php echo $category['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<section>
    <h2>Sports</h2>
    <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $request->setSql('SELECT sport.id AS sport_id, sport.nom AS sport_name, categorie.categorie AS category_name FROM sport JOIN categorie ON sport.categorie_id = categorie.id');
            $sportsWithCategories = $request->getLines($conn);
            
            foreach ($sportsWithCategories as $sport): ?>
                <tr>
                    <td><?php echo $sport['sport_name']; ?></td>
                    <td><?php echo $sport['category_name']; ?></td>
                    <td>
                        <a href="update_sport.php?id=<?php echo $sport['sport_id']; ?>">Edit</a> | 
                        <a href="delete_sport.php?id=<?php echo $sport['sport_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            
        </tbody>
    </table>
</section>

<section>
    <h2>Equipment</h2>
    <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Sport</th>
                <th>Categorie</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php 
            $request->setSql('SELECT equip.id AS equip_id, equip.nom AS equip_name, sport.nom AS sport_name, categorie.categorie AS category_name 
            FROM equipement AS equip 
            JOIN sport ON equip.sport_id = sport.id 
            JOIN categorie ON sport.categorie_id = categorie.id');
            $equipsWithSportsAndCategories = $request->getLines($conn);

            foreach ($equipsWithSportsAndCategories as $equip): ?>
            <tr>
                <td><?php echo $equip['equip_name']; ?></td>
                <td><?php echo $equip['sport_name']; ?></td>
                <td><?php echo $equip['category_name']; ?></td>
                <td>
                    <a href="update_equipement.php?id=<?php echo $equip['equip_id']; ?>">Edit</a> | 
                    <a href="delete_equipement.php?id=<?php echo $equip['equip_id']; ?>">Delete</a>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>


<section>
    <h2>Clients</h2>
    <table class="table table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>Email</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Age</th>
            <th>Téléphone</th>
            <th>Adresse complète</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
            <?php 
            $request->setSql('SELECT User.id AS user_id, User.email, User.prenom, User.nom, User.age, Client.telephone, 
                              Adresse.numero, Adresse.apartement, Adresse.rue, Adresse.ville, Adresse.codePostal, Adresse.province, Adresse.pays
                              FROM Client 
                              JOIN User ON Client.user_id = User.id
                              JOIN UserAdresse ON User.id = UserAdresse.user_id
                              JOIN Adresse ON UserAdresse.adresse_id = Adresse.id');
            $clients = $request->getLines($conn);
            foreach ($clients as $client): ?>
            <tr>
                <td><?php echo $client['email']; ?></td>
                <td><?php echo $client['prenom']; ?></td>
                <td><?php echo $client['nom']; ?></td>
                <td><?php echo $client['age']; ?></td>
                <td><?php echo $client['telephone']; ?></td>
                <td>
                    <?php 
                    echo $client['numero'] . ", " . 
                         $client['apartement'] . ", " . 
                         $client['rue'] . ", " . 
                         $client['ville'] . ", " . 
                         $client['codePostal'] . ", " . 
                         $client['province'] . ", " . 
                         $client['pays']; 
                    ?>
                </td>
                <td>
                    <a href="make_admin.php?user_id=<?php echo $client['user_id']; ?>">Rendre Admin</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

</body>
</html>
