<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Élève</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="main">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo-container">
            <a href="accEleve.php">
                <img src="img/img.png" alt="Logo" class="logo">
            </a>
        </div>
        <div class="nav-buttons">
            <button onclick="window.location.href='liste_cr.php'">Liste Compte Rendu</button>
            <button onclick="window.location.href='create_cr.php'">Créer un Compte Rendu</button>
            <button onclick="window.location.href='information.php'">Mes informations</button>
            <button onclick="window.location.href='logout.php'">Déconnexion</button>
        </div>
    </nav>
</body>
</html>