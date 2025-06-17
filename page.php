<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include 'conf.php';
session_start();

$varlogin = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8"); 
$varmdp = htmlentities($_POST['mdp'], ENT_QUOTES, "UTF-8");
$hashedPass = htmlentities(md5($varmdp));
//echo "test : $hashedPass";

$connexion = mysqli_connect($_serverBDD, $_userBDD, $_mdpBDD, $_nomBDD);

if (!$connexion) {
    die("Erreur de connexion à la base de données.");
}

// Préparer et exécuter la requête pour éviter les injections SQL
$stmt = mysqli_prepare($connexion, "SELECT * FROM users WHERE login = ? AND mdp = ?");
mysqli_stmt_bind_param($stmt, 'ss', $varlogin, $hashedPass);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Vérifier si un utilisateur a été trouvé
if (mysqli_num_rows($result) == 0 && password_verify($varmdp, $hashedPass)) {
    echo "Le pseudo ou le mot de passe est incorrect, le compte n'a pas été trouvé.";
} else {
    $user = mysqli_fetch_assoc($result);
    $id_statut = $user['id_statut'];
    $id_mode = $user['id_mode'];
    $lock = $user['lock']; 
    //echo "mdp : $hashedPass, login : $varlogin, and : $varmdp";
    
    
    //echo id_mode;
    
    
    
    // Gestion du thème basé sur id_mode


    if ($id_mode == 2) {
        $_SESSION['theme'] = 'dark';
    } else {
        $_SESSION['theme'] = 'light'; // par défaut
    }



    $_SESSION['login'] = $varlogin;
    echo $id_statut;
    if ($lock == 0){
        // Rediriger selon le statut de l'utilisateur
        if ($id_statut == 1) {
            header('location: accEleve.php');
        } elseif ($id_statut == 2) {
            header('location: accProf.php');   
        } else {
            echo "Erreur : vous n'avez pas de statut valide.";
        }
        exit;
    } else {
        echo "Votre compte a été bloqué, veuillez contacter l'administrateur.";
    }
        
}
?>
