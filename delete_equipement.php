<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
session_start();

if (isset($_GET['id'])) {
    $equipement_id = $_GET['id'];

    $dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
    $conn = $dbConn->connexion();
    $request = new Request();

    // Si la suppression est confirmée
    if (isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
        // Exécutez la requête DELETE
        $request->setSql("DELETE FROM Equipement WHERE id = :id");
        $params = ['id' => $equipement_id];
        $request->getLines($conn, $params);

        // Fermez la connexion
        $dbConn->deconnexion();

        // Redirigez vers la page d'admin avec un message
        header("Location: admin_dashboard.php?msg=Equipement supprimé avec succès");
        exit;
    } else {
        // Si la suppression n'est pas encore confirmée
        // Obtenir le nom de l'équipement
        $request->setSql("SELECT nom FROM Equipement WHERE id = :id");
        $params = ['id' => $equipement_id];
        $equipement = $request->getLines($conn, $params, true);

        if ($equipement) {
            echo "Voulez-vous vraiment supprimer l'équipement: " . $equipement['nom'] . "?";
            echo "<br><a href='delete_equipement.php?id={$equipement_id}&confirm=true'>Oui</a> | <a href='admin_dashboard.php'>Non</a>";
        } else {
            echo "L'équipement avec cet ID n'a pas été trouvé.";
        }
    }
} else {
    echo "Aucun ID d'équipement fourni.";
}
?>
