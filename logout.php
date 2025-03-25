<?php
session_start();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/'); // Supprime le cookie de session
header("Location: index.php"); // La page de connexion
?>
