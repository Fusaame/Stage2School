<?php

session_start();

include 'navbarProf.php';

$nameProf = $_SESSION['login'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Professeur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body name="main" id="main">
    <div class="first">
        <!-- Contenu principal -->
        <div id="test">
            <h1>Bonjour, <?php echo $nameProf; ?> !</h1>
            <h2>Bienvenue dans votre Espace Professeur !</h2>
            <p>Accédez aux différentes sections à l'aide des boutons ci-dessus pour gérer vos comptes rendus et commentaires.</p>
            <button onclick="window.location.href='list_cr_prof.php'">Liste Compte Rendu</button>
            <button onclick="window.location.href='logout.php'">Déconnexion</button>
        </div>
    </div>
    
</body>
</html>
