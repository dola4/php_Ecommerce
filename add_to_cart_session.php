<?php

include './includes/chargement.php';
include './includes/navbar_client.php';

session_start();

// Initialiser le panier si nécessaire
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $equipementId = $_POST['equipement_id'];
    $quantite = $_POST['quantite'] ?? 1;

    // Si l'équipement est déjà dans le panier, mettez à jour la quantité
    if (isset($_SESSION['panier'][$equipementId])) {
        $_SESSION['panier'][$equipementId] += $quantite;
    } else {
        // Sinon, ajoutez un nouvel équipement au panier
        $_SESSION['panier'][$equipementId] = $quantite;
    }

    // Redirigez vers la page du panier
    header('Location: show_cart.php');
    exit;
} else {
    header('Location: error.php?msg=Requête invalide');
    exit;
}
