<?php
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
session_start();
$dbConn = new DatabaseConnexion(HOST, DBNAME, DBUSER, DBPASSWORD);
$conn = $dbConn->connexion();

$request = new Request();

// Vérifiez si l'ID de la catégorie est défini
if(isset($_GET['id'])) {
    $categorie_id = $_GET['id'];

    if(isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
        // Si la suppression est confirmée

        // 1. Supprimez les équipements liés aux sports de cette catégorie
        $request->setSql("DELETE equip FROM equipement AS equip 
                          JOIN sport ON sport.id = equip.sport_id 
                          WHERE sport.categorie_id = :id");
        $params = ['id' => $categorie_id];
        $request->getLines($conn, $params);

        // 2. Supprimez les sports associés à cette catégorie
        $request->setSql("DELETE FROM Sport WHERE categorie_id = :id");
        $request->getLines($conn, $params);

        // 3. Supprimez la catégorie
        $request->setSql("DELETE FROM Categorie WHERE id = :id");
        $request->getLines($conn, $params);

        // Fermez la connexion
        $dbConn->deconnexion();

        // Message de confirmation
        echo "La catégorie, ses sports et ses équipements ont été supprimés avec succès!";
        echo "<a href='admin_dashboard.php'>Retour au tableau de bord</a>";

    } else {
        // Si la suppression n'est pas confirmée, affichez les détails

        // Obtenir les détails de la catégorie
        $request->setSql("SELECT * FROM Categorie WHERE id = :id");
        $categorie = $request->getLines($conn, ['id' => $categorie_id], true);

        // Obtenir les sports pour cette catégorie 

        $request->setSql("SELECT * FROM Sport WHERE categorie_id = :id");
        $sports = $request->getLines($conn, ['id' => $categorie_id]);

        echo "<h1>{$categorie['categorie']}</h1>";

        foreach($sports as $sport) {
            echo "<h2>{$sport['nom']}</h2>";

            // Obtenir les équipements pour ce sport
            $request->setSql("SELECT * FROM equipement WHERE sport_id = :id");
            $equipements = $request->getLines($conn, ['id' => $sport['id']]);

            echo "<table border='1'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom de l'équipement</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach($equipements as $equipement) {
                echo "<tr>
                        <td>{$equipement['id']}</td>
                        <td>{$equipement['nom']}</td>
                        <td>{$equipement['description']}</td>
                      </tr>";
            }

            echo "</tbody>
                </table>";
        }

        echo "<a href='?id={$categorie_id}&confirm=true'>Confirmer la suppression</a>";
        echo "<a href='admin_dashboard.php'>Retour au tableau de bord</a>";
    }
} else {
    echo "Aucun ID de catégorie fourni.";
}
?>
