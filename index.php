<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styleForm.css">
</head>
<body>
    <div id="formulaire">
        <form method="POST" action="page.php">
            <h2>Connexion</h2>
            
            <label for="login">Votre login</label>
            <input type="text" id="login" name="login" placeholder="Login" required>
            
            <label for="mdp">Mot de passe</label>
            <input type="password" id="mdp" name="mdp" placeholder="Mot de passe" required>
            
            <input type="submit" name="send_connexion" value="Login">
            
            <div class="links">
                <a href="oubli.php">Mot de passe oublié ?</a>
                <!--<a href="create.php">Créer un compte ?</a>-->
            </div>
        </form>
    </div>
</body>
</html>
