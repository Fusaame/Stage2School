<?php 
include 'conf.php';
include 'navbar.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Récupérer les infos utilisateur
$stmt = $connexion->prepare("SELECT * FROM users WHERE login = ?");
$stmt->bind_param("s", $nameEleve);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0){
    $user = $result->fetch_assoc();
    $id_stage = $user['id'];
} else {
    die("Aucune donnée trouvée pour cet utilisateur.");
}

// Gestion du thème via GET (mise à jour base + session)
$needReload = false;
if (isset($_GET['theme'])) {
    // Mapping : 'dark' => 2, 'light' => 1
    $newThemeInt = ($_GET['theme'] === 'dark') ? 2 : 1;

    $stmtUpdate = $connexion->prepare("UPDATE users SET id_mode = ? WHERE id = ?");
    if ($stmtUpdate === false) {
        die("Erreur de préparation : " . $connexion->error);
    }
    $stmtUpdate->bind_param("ii", $newThemeInt, $id_stage);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    $_SESSION['theme'] = $newThemeInt;

    $needReload = true;
}

// Définir le thème actuel (depuis session ou user DB, sinon clair par défaut)
if (isset($_SESSION['theme'])) {
    $theme = ($_SESSION['theme'] == 2) ? 'dark' : 'light';
} else {
    // Si pas en session, on prend la valeur en base
    $theme = ($user['id_mode'] == 2) ? 'dark' : 'light';
    $_SESSION['theme'] = $user['id_mode'];
}

// Récupérer les infos stage
$stmt2 = $connexion->prepare("SELECT * FROM stage WHERE id_user = ?");
$stmt2->bind_param("i", $id_stage);
$stmt2->execute();
$result2 = $stmt2->get_result();

if($result2->num_rows > 0){
    $stage = $result2->fetch_assoc();
} else {
    $stage = []; // Pour éviter erreurs si stage inexistant
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil de l'élève</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 80px;
            padding-bottom: 40px;
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            transition: background-color 0.3s, color 0.3s;
            background-color: <?= $theme === 'dark' ? '#222' : '#f9f9f9' ?>;
            color: <?= $theme === 'dark' ? '#eee' : '#000' ?>;
        }
        .container, .container2 {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            padding: 20px;
            text-align: center;
            background: <?= $theme === 'dark' ? '#333' : '#fff' ?>;
            color: <?= $theme === 'dark' ? '#eee' : '#000' ?>;
        }
        .info {
            margin: 15px 0;
            padding: 10px;
            border-radius: 4px;
            background: <?= $theme === 'dark' ? '#444' : '#f1f1f1' ?>;
        }
        .theme-toggle {
            position: fixed;
            padding-top: 100px;
            right: 20px;
            z-index: 1000;
        }
        .theme-toggle a {
            padding: 10px 15px;
            background: #007BFF;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .theme-toggle a:hover {
            background: #0056b3;
        }
    </style>

    <?php if ($needReload): ?>
    <script>
        // Recharge la page sans parametres GET pour éviter boucle
        window.history.replaceState(null, null, window.location.pathname);
        window.location.reload();
    </script>
    <?php endif; ?>


</head>
<body class="<?= htmlspecialchars($theme) ?>">

    <div class="container">
        <h1>Profil de l'élève</h1>
        <div class="info"><strong>Nom :</strong> <?= htmlspecialchars($user['nom']) ?></div>
        <div class="info"><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom']) ?></div>
        <div class="info"><strong>Date de naissance :</strong> <?= htmlspecialchars($user['dateN']) ?></div>
        <div class="info"><strong>Email :</strong> <?= htmlspecialchars($user['mail']) ?></div>
        <div class="info"><strong>Téléphone :</strong> <?= htmlspecialchars($user['tel']) ?></div>
    </div>

    <div class="container2">
        <h1>Entreprise de l'élève</h1>
        <div class="info"><strong>Entreprise :</strong> <?= htmlspecialchars($stage['monEntreprise'] ?? '') ?></div>
        <div class="info"><strong>Mission du Stage :</strong> <?= htmlspecialchars($stage['titre'] ?? '') ?></div>
        <div class="info"><strong>Date de début :</strong> <?= htmlspecialchars($stage['dateD'] ?? '') ?></div>
        <div class="info"><strong>Date de fin :</strong> <?= htmlspecialchars($stage['dateF'] ?? '') ?></div>
        <div class="info"><strong>Tuteur :</strong> <?= htmlspecialchars($stage['monTuteur'] ?? '') ?></div>
        <div class="info"><strong>Téléphone :</strong> <?= htmlspecialchars($stage['telTuteur'] ?? '') ?></div>
        <div class="info"><strong>Adresse :</strong> <?= htmlspecialchars($stage['adresse'] ?? '') ?></div>
        <div class="info"><strong>Ville :</strong> <?= htmlspecialchars($stage['ville'] ?? '') ?></div>
        <div class="info"><strong>Code Postal :</strong> <?= htmlspecialchars($stage['codePostal'] ?? '') ?></div>
    </div>

    <div class="theme-toggle">
        <?php if ($theme === 'dark'): ?>
            <a href="?theme=light" title="Passer au thème clair">☀️</a>
        <?php else: ?>
            <a href="?theme=dark" title="Passer au thème sombre">🌙</a>
        <?php endif; ?>
    </div>

</body>
</html>
