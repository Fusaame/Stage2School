<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'conf.php';
include 'navbarProf.php';

session_start();

$id_user = isset($_GET['id_user']) ? intval($_GET['id_user']) : null;

if ($id_user === null) {
    die("ID utilisateur non spécifié.");
}

//echo "ID élève : $id_user";

$connexion = mysqli_connect($_serverBDD, $_userBDD, $_mdpBDD, $_nomBDD);

if (!$connexion) {
    die("Erreur de connexion à la base de données.");
} 

// Requête securise pour recuperer les données de l'utilisateur
$stmt = $connexion->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    //echo "Nom de l'utilisateur : " . $user['nom'];
} else {
    die("Aucune donnée trouvée pour cet utilisateur.");
}
$stmt2 = $connexion->prepare("SELECT * FROM stage WHERE id_user = ?");
$stmt2->bind_param("i", $id_user);
$stmt2->execute();
$result2 = $stmt2->get_result();

if($result2->num_rows>0){
    $stage = $result2->fetch_assoc();
    //echo "Nom de l'entreprise :".$stage['monEntreprise'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de l'élève</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            padding: 20px;
            text-align: center;
        }
        .container2 {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin-left: 20px;
            padding: 20px;
            text-align: center;
        }
        .container h1 {
            font-size: 24px;
            color: #007BFF;
        }
        .container2 h1 {
            font-size: 24px;
            color: #007BFF;
        }
        .info {
            margin: 15px 0;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 4px;
        }
        .info strong {
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Eleve -->
    <div class="container">
        <h1>Profil de l'élève</h1>
        <div class="info"><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></div>
        <div class="info"><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></div>
        <div class="info"><strong>Date de naissance :</strong> <?= htmlspecialchars($user['dateN']) ?></div>
        <div class="info"><strong>Email :</strong> <?= htmlspecialchars($user['mail']) ?></div>
        <div class="info"><strong>Téléphone :</strong> <?= htmlspecialchars($user['tel']) ?></div>
    </div>
    <!-- Entreprise -->
    <div class="container2">
        <h1>Entreprise de l'élève</h1>
        <div class="info"><strong>Entreprise :</strong> <?= htmlspecialchars($stage['monEntreprise']) ?></div>
        <div class="info"><strong>Mission du Stage :</strong> <?= htmlspecialchars($stage['titre']) ?></div>
        <div class="info"><strong>Date de début :</strong> <?= htmlspecialchars($stage['dateD']) ?></div>
        <div class="info"><strong>Date de fin :</strong> <?= htmlspecialchars($stage['dateF']) ?></div>
        <div class="info"><strong>Tuteur :</strong> <?= htmlspecialchars($stage['monTuteur']) ?></div>
        <div class="info"><strong>Téléphone :</strong> <?= htmlspecialchars($stage['telTuteur']) ?></div>
        <div class="info"><strong>Adresse :</strong> <?= htmlspecialchars($stage['adresse']) ?></div>
        <div class="info"><strong>Ville :</strong> <?= htmlspecialchars($stage['ville']) ?></div>
        <div class="info"><strong>Code Postal :</strong> <?= htmlspecialchars($stage['codePostal']) ?></div>
    </div>
</body>
</html>
