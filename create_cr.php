<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conf.php';
include 'navbar.php';

session_start();

if (!isset($_SESSION['login'])) {
    header('location: index.php');
    exit;
}

$connexion = mysqli_connect($_serverBDD, $_userBDD, $_mdpBDD, $_nomBDD);

if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

$nameEleve = $_SESSION['login'];
$query = "SELECT id FROM users WHERE login = '$nameEleve'";
$result = mysqli_query($connexion, $query);
$id = $result ? mysqli_fetch_assoc($result)['id'] : null;
$message = '';

if (isset($_POST['send_newCR'])) {
    $newSujet = mysqli_real_escape_string($connexion, $_POST['sujet']);
    $newContenu = mysqli_real_escape_string($connexion, $_POST['contenu']);
    $noteCR = $_POST['note'];

    // Verifie si la note est "Non renseigne" et attribue NULL si nécessaire
    if ($noteCR === 'null') {
        $noteCR = 'NULL'; // Préparez la valeur NULL pour SQL
        echo $noteCR; // on verifie si la valeur est bien nulle
    } else {
        $noteCR = (int) $noteCR; // On convertit en entier
    }

    $requete = "INSERT INTO CR(sujet, contenu, date_creation, date_modif, note, vu, id_user) 
                VALUES('$newSujet', '$newContenu', NOW(), NOW(), $noteCR, 0, $id)";
    
    if (mysqli_query($connexion, $requete)) {
        $message = "Compte-rendu enregistré avec succès!";
    } else {
        $message = "Erreur lors de l'enregistrement : " . mysqli_error($connexion);
    }
}

mysqli_close($connexion);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Compte-Rendu</title>
    <link rel="stylesheet" href="create_cr.css">
</head>
<body>
    <div id="formulaire">
        <h1>Créer votre compte-rendu</h1>
        <!-- On affiche le message de confirmation si le CR a été créé -->
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="text" name="sujet" placeholder="Sujet" required>
            <textarea name="contenu" placeholder="Contenu de votre Compte-rendu" rows="5" required></textarea>
            <input type="date" name="date">
            <label for="note">Note du Compte-rendu</label>
            <select id="note-select" name="note" required>
                <option value="null">Non renseigné</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <input type="submit" name="send_newCR" value="Enregistrer">
        </form>
    </div>
</body>
</html>
