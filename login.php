<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

$message = "";
session_start();
  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['pw'];

    $dbConnection = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
    $conn = $dbConnection->connexion();

    $request = new Request();
    $request->setSql('SELECT * FROM User WHERE email = :email');
    $userData = $request->getLines($conn, ['email' => $email], true);

    if ($email == $userData['email'] && password_verify($password, $userData['pw'])) {

        
        $userObject = new User($userData['id'], $userData['nom'], $userData['prenom'], $userData['age'], $userData['email'], $userData['pw']);
        $_SESSION['user'] = $userObject;

        // Determine if the user is an admin or a client.
        $request->setSql('SELECT * FROM Admin WHERE user_id = :id');
        $adminData = $request->getLines($conn, ['id' => $userData['id']], true);

        $request->setSql('SELECT * FROM Client WHERE user_id = :id');
        $clientData = $request->getLines($conn, ['id' => $userData['id']], true);

        if ($adminData) {
            $_SESSION['user_type'] = 'admin';
            header('Location: admin_dashboard.php');
            exit;
        } elseif ($clientData) {
            $_SESSION['user_type'] = 'client';
            header('Location: boutique.php');
            exit;
        } else {
            $message = "User role not recognized.";
        }

    } else {
        $message = 'Invalid email or password';
    }

    $dbConnection->deconnexion();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Login</h3>
                </div>
                <div class="card-body">

                    <?php
                    if (!empty($message)) {
                        echo "<div class='alert alert-danger'>$message</div>";
                    }
                    ?>

                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="pw">Mot de passe</label>
                            <input type="password" name="pw" id="pw" class="form-control" required>
                        </div>
                        <input type="submit" value="Se connecter" class="btn btn-primary btn-block">
                        <a href="register.php" class="btn btn-link btn-block">S'inscrire</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>