<?php
session_start();

// Supprime toutes les variables de session.
$_SESSION = array();

// Si vous souhaitez détruire complètement la session, supprimez également le cookie de session.
// Note : cela détruira la session et pas seulement les données de session.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalement, on détruit la session.
session_destroy();

// Redirige vers la page de connexion (ou n'importe quelle autre page).
header("Location: login.php");
exit;
?>
