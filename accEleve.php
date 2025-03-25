<?php

session_start();

$nameEleve = $_SESSION['login'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Élève</title>
    <link rel="stylesheet" href="style.css">
</head>
<body name="main" id="main">
    <div class="first">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="logo-container">
                <img src="img/img.png" alt="Logo" class="logo">
            </div>
            <div class="nav-buttons">
                <button onclick="window.location.href='liste_cr.php'">Liste Compte Rendu</button>
                <button onclick="window.location.href='create_cr.php'">Créer un Compte Rendu</button>
                <button onclick="window.location.href='information.php'">Mes informations</button>
                <button onclick="window.location.href='logout.php'">Déconnexion</button>
            </div>
        </nav>

        <!-- Contenu principal -->
        <div id="test">
            <h1>Bonjour, <?php echo $nameEleve; ?> !</h1>
            <h2>Bienvenue dans votre Espace Élève !</h2>
            <p>Accédez aux différentes sections à l'aide des boutons ci-dessus pour gérer vos comptes rendus et commentaires.</p>
            <button onclick="window.location.href='liste_cr.php'">Liste Compte Rendu</button>
            <button onclick="window.location.href='create_cr.php'">Créer un Compte Rendu</button>
            <button onclick="window.location.href='information.php'">Mes informations</button>
            <button onclick="window.location.href='logout.php'">Déconnexion</button>
        </div>
    </div>
    
</body>
</html>
