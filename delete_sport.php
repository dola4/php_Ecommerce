<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
session_start();

$dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
$conn = $dbConn->connexion();

$request = new Request();

// Vérifiez si l'ID du sport est défini
if(isset($_GET['id'])) {
    $sport_id = $_GET['id'];

    if(isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
        // Si la suppression est confirmée

        // 1. Supprimez les équipements liés à ce sport
        $request->setSql("DELETE FROM equipement WHERE sport_id = :id");
        $params = ['id' => $sport_id];
        $request->getLines($conn, $params, true);

        // 2. Supprimez le sport en question
        $request->setSql("DELETE FROM Sport WHERE id = :id");
        $request->getLines($conn, $params);

        // Fermez la connexion
        $dbConn->deconnexion();

        // Message de confirmation
        echo "Le sport et ses équipements ont été supprimés avec succès!";
        echo "<a href='admin_dashboard.php'>Retour au tableau de bord</a>";

    } else {
        // Si la suppression n'est pas confirmée, affichez les détails

        // Obtenir les détails du sport
        $request->setSql("SELECT * FROM Sport WHERE id = :id");
        $sport = $request->getLines($conn, ['id' => $sport_id], true);
        // var_dump($sport); 

        // Obtenir les équipements pour ce sport
        $request->setSql("SELECT * FROM equipement WHERE sport_id = :id");
        $equipements = $request->getLines($conn, ['id' => $sport_id]);
        

        if (is_array($sport) && isset($sport['nom'])) {
            echo "<h1>{$sport['nom']}</h1>";
        } else {
            echo "Error: Unexpected data format for sport.";
        }
        echo "<table border='1'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom de l'équipement</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>";
                // var_dump($equipements);
                foreach($equipements as $equipement) {
                    if (is_array($equipement) && isset($equipement['id'], $equipement['nom'], $equipement['description'])) {
                        echo "<tr>
                            <td>{$equipement['id']}</td>
                            <td>{$equipement['nom']}</td>
                            <td>{$equipement['description']}</td>
                        </tr>";
                    }
                    else {
                        echo "Aucun équipement trouvé pour ce sport.";
                    }
                }        

        echo "</tbody>
            </table>";

        echo "<a href='?id={$sport_id}&confirm=true'>Confirmer la suppression</a>";
        echo "<a href='admin_dashboard.php'>Retour au tableau de bord</a>";
    }
} else {
    echo "Aucun ID de sport fourni.";
}
?>
