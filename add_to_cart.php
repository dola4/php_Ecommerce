<?php

include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';
include './includes/navbar_client.php';

session_start();

function getSportObjectById($sportId) {
    global $conn;
    $request = new Request();
    $request->setSql("SELECT * FROM Sport WHERE id = :sport_id");
    $sports = $request->getLines($conn, ["sport_id" => $sportId]);
    
    if ($sports) {
        $sportData = $sports[0];
        $categorieId = $sportData['categorieId'] ?? null;
        return new Sport($sportData['id'], $sportData['nom'], $sportData['description'], $sportData['image'], $categorieId);
    }
    return null;
}

$user_id = $_SESSION['user']->getUser_id();

$dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
$conn = $dbConn->connexion();

$request = new Request();
$request->setSql("SELECT id FROM Client WHERE user_id = :user_id");
$clientData = $request->getLines($conn, ["user_id" => $user_id]);
echo "User ID from session: " . $_SESSION['user']->getUser_id();

$request ->setSql("SELECT id FROM admin WHERE user_id = :user_id");
$adminData = $request->getLines($conn, ["user_id" => $user_id]);

/* echo "client data <pre>";
print_r($clientData);
echo "</pre>";
echo "session data <pre>";
print_r($_SESSION['user']);
echo "</pre>";
 */

if($clientData ) {
    // Redirigez vers une page d'erreur ou traitez l'erreur comme vous le souhaitez.
    die("L'utilisateur n'existe pas dans la base de données.");
}

// Vérification du type d'utilisateur (client ou admin)
if ($clientData || $adminData) {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $equipementId = $_POST['equipement_id'];
        $quantite = $_POST['quantite'] ?? 1;

        $request->setSql("SELECT id, nom, description, prix, image, sport_id FROM Equipement WHERE id = :equipement_id");
        $equipements = $request->getLines($conn, ["equipement_id" => $equipementId]);
        echo "equipement <pre>";
        print_r($equipements);
        echo "</pre>";
        $request->setSql("SELECT id FROM Panier WHERE user_id = :user_id");
        $paniers = $request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id()]);
        echo "panier <pre>";
        print_r($paniers);
        echo "</pre>";  

        if (empty($paniers)) {
            $request->setSql("INSERT INTO Panier (user_id) VALUES (:user_id)");
            $request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id()]);
            // Récupérer l'ID du panier récemment créé
            $panier_id = $conn->lastInsertId();
            echo "panier id <pre>";
            print_r($panier_id);
            echo "</pre>";
            // Mise à jour du champ panier_id dans la table User
            $request->setSql("UPDATE User SET panier_id = :panier_id WHERE id = :user_id");
            $request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id(), "panier_id" => $panier_id]);
        } else {
            $panier_id = $paniers[0]['id'];
        }
        

        if (!empty($equipements)) {
            $equipementData = $equipements[0];
            $sportObject = getSportObjectById($equipementData['sport_id']);
            echo "sport <pre>";
            print_r($sportObject);
            echo "</pre>";
            if ($sportObject !== null) {
                $equipement = new Equipement($equipementData['id'], $equipementData['nom'], $equipementData['description'], $equipementData['prix'], $equipementData['image'], $sportObject);

                $request->setSql("SELECT * FROM PanierEquipement WHERE panier_id = :panier_id AND equipement_id = :equipement_id");
                $result = $request->getLines($conn, [
                    "panier_id" => $_SESSION['user']->getUser_id(),
                    "equipement_id" => $equipement->getId()
                ]);

                $found = !empty($result);
                echo "result <pre>";
                print_r($result);
                echo "</pre>";
                echo "Found <pre>";
                print_r($found);
                echo "</pre>";
                if ($found) {
                    $request->setSql("UPDATE PanierEquipement SET quantite = quantite + :quantite WHERE panier_id = :panier_id AND equipement_id = :equipement_id");
                } else {
                    $request->setSql("INSERT INTO PanierEquipement (panier_id, equipement_id, quantite) VALUES (:panier_id, :equipement_id, :quantite)");
                }

                $request->getLines($conn, [
                    "panier_id" => $_SESSION['user']->getUser_id(),
                    "equipement_id" => $equipement->getId(),
                    "quantite" => $quantite
                ]);

                if (!isset($_SESSION['panier'])) {
                    $_SESSION['panier'] = new Panier($_SESSION['user']);
                }

                $_SESSION['panier']->ajouterArticle($equipement, $quantite);
                header('Location: show_cart.php');
            } else {
                header('Location: error.php?msg=Erreur lors de la récupération de l\'objet Sport');
            }
        } else {
            header('Location: error.php?msg=Equipement non trouvé');
        }
    }

} else {
    // Traitez ici le cas où l'utilisateur est un admin ou un autre type d'utilisateur
    // Vous pourriez aussi rediriger vers une page d'erreur spécifique pour les admins s'ils ne sont pas autorisés à ajouter des articles au panier
    header('Location: error.php?msg=Utilisateur non autorisé');
}

$dbConn->deconnexion();

?>
