<link rel="stylesheet" href="styleForm.css">
<div id="formulaire">
    <form method="POST" action="page.php">
        <h1>Stage2School</h1>

        <label for="email">Email</label>
        <input type="text" name="email" placeholder="Votre email" required>

        <label for="nom">Nom</label>
        <input type="text" name="nom" placeholder="Votre nom" required>

        <label for="prenom">Prénom</label>
        <input type="text" name="login" placeholder="Votre prénom" required>

        <label for="tel">Téléphone</label>
        <input type="text" name="tel" placeholder="Numéro">

        <label for="mdp">Mot de passe</label>
        <input type="password" name="mdp" placeholder="Mot de passe" required>
        <input type="password" name="verifmdp" placeholder="Confirmez votre mot de passe" required>

        <label for="statut">Statut</label>
        <div>
            <input type="radio" id="statut-prof" name="statut" value="prof" required>
            <label for="statut-prof">Prof</label>

            <input type="radio" id="statut-eleve" name="statut" value="eleve" required>
            <label for="statut-eleve">Élève</label>
        </div>
        <div class="date-container">
            <label for="dateN">Date de naissance</label>
            <input type="date" name="date" required>
        </div>

        <input type="submit" name="create_login" value="Inscription">
    </form>
</div>
