<?php
include 'conf.php';

# Fonction pour générer un mot de passe aléatoire
function mdpAlea(){
    $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $nb_caract = 12;
    $pass = "";
    for($u = 1; $u <= $nb_caract; $u++) {
        $nb = strlen($chaine);
        $nb = mt_rand(0,($nb-1));
        $pass .= $chaine[$nb];
    }
    return $pass;
}

# Vérification si le formulaire a été soumis
if (isset($_POST['send_lostmdp'])) {

    # On récupère l'email entré par l'utilisateur
    $lemail = $_POST['email'];

    # Connexion à la base de données
    $connexion = mysqli_connect($_serverBDD, $_userBDD, $_mdpBDD, $_nomBDD);

    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }

    # Vérification si l'email existe dans la base
    $requete = "SELECT mail FROM users WHERE mail = ?";
    $stmt = mysqli_prepare($connexion, $requete);

    if (!$stmt) {
        die("Erreur de préparation de la requête : " . mysqli_error($connexion));
    }

    # on remplace le ? par $lemail
    mysqli_stmt_bind_param($stmt, "s", $lemail);
    mysqli_stmt_execute($stmt);
    $resultat = mysqli_stmt_get_result($stmt);

    if ($donnees = mysqli_fetch_assoc($resultat)) {

        # Génération d'un nouveau mot de passe
        $newmdp = mdpAlea();

        # Hash du nouveau mot de passe
        $empreinte = md5($newmdp);

        # affichage des variables pour voir si ça correspond
        #echo "Email: " . $lemail . "<br>";
        #echo "Nouveau mot de passe (hashé): " . $empreinte . "<br>";

        # 
        $requete_update = "UPDATE users SET mdp = ? WHERE mail = ?";
        $stmt_update = mysqli_prepare($connexion, $requete_update);

        if (!$stmt_update) {
            die("Erreur : " . mysqli_error($connexion));
        }

        # Onj remplace les ? par les variables $empreinte et $lemail
        mysqli_stmt_bind_param($stmt_update, "ss", $empreinte, $lemail);

        # Exécution de la requête de mise à jour
        if (mysqli_stmt_execute($stmt_update)) {
            echo "Mot de passe changé avec succès.";

            # Préparation de l'email
            $to = $lemail;
            $subject = "Votre nouveau mot de passe";
            $message = "Voici votre nouveau mot de passe sécurisé : ".$newmdp;
            $headers = "From: fusaame95@gmail.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

            # Envoi de l'email
            if (mail($to, $subject, $message, $headers)) {
                echo "Email envoyé.";
            } else {
                echo "L'email n'a pas pu être envoyé.";
            }
        } else {
            echo "Erreur lors de la mise à jour du mot de passe : " . mysqli_error($connexion);
        }

    } else {
        echo "Cet email n'existe pas.";
    }

    mysqli_close($connexion);
}
?>
<link rel="stylesheet" href="styleOublie.css">
<form method="POST" action="">
    <input class="oublimdp" type="text" name="email" placeholder="Votre email" required>
    <input type="submit" name="send_lostmdp" value="Confirmer">
</form>
