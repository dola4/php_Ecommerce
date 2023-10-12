<?php
include './includes/navbar_client.php';
include './includes/chargement.php';
include './app/config/database.php';
include './asset/bd/env.php';

session_start();

function getSportById($id, $conn)
{
    $request = new Request();
    $request->setSql("SELECT * FROM Sport WHERE id = :id");
    $result = $request->getLines($conn, ["id" => $id], true);
    if (empty($result)) {
        return null;
    }
    $sportData = $result;
    return new Sport($sportData['id'], $sportData['nom'], $sportData['description'], $sportData['image'], $sportData['categorie_id']);
}

$dbConn = new DatabaseConnexion($GLOBALS['DBHOST'], $GLOBALS['DBNAME'], $GLOBALS['DBUSER'], $GLOBALS['DBPASS']);
$conn = $dbConn->connexion();

$request = new Request();

// Récupérez l'ID du panier de l'utilisateur
$request->setSql("SELECT id FROM Panier WHERE user_id = :user_id");
$panier = $request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id()], true);

/* echo "panier <pre>";
print_r($panier);
echo "</pre>"; */

if (empty($panier)) {
    // Si l'utilisateur n'a pas de panier, créez-en un
    $request->setSql("INSERT INTO Panier (user_id) VALUES (:user_id)");
    $request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id()]);
}

/* echo "request <pre>";
print_r($request);
echo "</pre>"; */

// Récupérez l'ID du panier de l'utilisateur
$request->setSql("SELECT id FROM Panier WHERE user_id = :user_id");
$panier = $request->getLines($conn, ["user_id" => $_SESSION['user']->getUser_id()], true);

/* echo "panier 2 <pre>";
print_r($panier);
echo "</pre>"; */

if (empty($panier)) {
    echo "Votre panier est vide.";
    exit;
}

$panier_id = $panier['id'];

/* echo "panier id <pre>";
print_r($panier_id);
echo "</pre>"; */

// Récupérez les articles du panier
$request->setSql("SELECT e.id AS equipement_id, e.nom, e.description, e.prix, e.image, e.sport_id, pe.quantite FROM PanierEquipement pe JOIN Equipement e ON pe.equipement_id = e.id WHERE pe.panier_id = :panier_id");
$articles = $request->getLines($conn, ["panier_id" => $panier_id]);

/* echo "<pre>";
print_r($articles);
echo "</pre>"; */

$monPanier = new Panier($_SESSION['user']);
foreach ($articles as $article) {
    $sport = getSportById($article['sport_id'], $conn);
    $equipement = new Equipement(
        $article['equipement_id'],
        $article['nom'],
        $article['description'],
        $article['prix'],
        $article['image'],
        $sport
    );

    $monPanier->chargerArticle($equipement, $article['quantite']);

/* echo "<pre>";
print_r($monPanier);
echo "</pre>"; */

}
$total = $monPanier->calculerTotal();

/* echo "<pre>";
print_r($total);
echo "</pre>"; */

?>
<div class="container mt-5">
    <h2>Votre panier:</h2>
    <?php if (count($articles) > 0) : ?>
        <h3>Total : $<?= $total ?> </h3>
        <ul class="list-group">
            <?php foreach ($articles as $article) : ?>
                <li class="list-group-item">
                    <strong>Nom:</strong> <?= $article['nom'] ?><br>
                    <strong>Prix:</strong> <?= $article['prix'] ?> $<br>
                    <strong>Quantité:</strong> <?= $article['quantite'] ?><br>

                    <!-- Formulaire pour modifier la quantité -->
                    <form action='update_cart.php' method='post' class="form-inline mt-2">
                        <input type='hidden' name='action' value='update'>
                        <input type='hidden' name='equipement_id' value='<?= $article['equipement_id'] ?>'>
                        <div class="form-group">
                            <label for='quantite'>Quantité :</label>
                            <input type='number' name='quantite' value='<?= $article['quantite'] ?>' class="form-control ml-2">
                        </div>
                        <input type='submit' value='Mettre à jour' class="btn btn-warning ml-2">
                    </form>

                    <!-- Formulaire pour supprimer l'article -->
                    <form action='update_cart.php' method='post' class="mt-2">
                        <input type='hidden' name='action' value='delete'>
                        <input type='hidden' name='equipement_id' value='<?= $article['equipement_id'] ?>'>
                        <input type='submit' value='Supprimer' class="btn btn-danger">
                    </form>

                </li>
            <?php endforeach; ?>
        </ul>
        <form action='create_order.php' method='post' class="mt-3">
            <input type='submit' value='Commander' class="btn btn-success">
        </form>
        <div id="paypal-button-container" class="mt-3"></div>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?= $total ?>'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        // Appel AJAX à create_order.php
                        $.post('create_order.php', function(response) {
                            if (response.success) {
                                <?php
                                $response = ['success' => true, 'message' => 'Commande créée avec succès!'];
                                echo json_encode($response);
                                ?>
                                // Ici, vous pouvez rediriger l'utilisateur ou effectuer d'autres actions.
                                window.location.href = "create_order.php";
                            } else {
                                alert('Erreur lors de la création de la commande.');
                            }
                        }, 'json');
                    });
                }
            }).render('#paypal-button-container');
        </script>

    <?php else : ?>
        <p>Votre panier est vide.</p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="#"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php
$dbConn->deconnexion();
?>