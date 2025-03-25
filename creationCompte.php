<?php

include 'conf.php';
// Set up pour afficher toute les erreurs avec les détails
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// On recupere les infos
$email = htmlentities($_POST['email'], ENT_QUOTES, "UTF-8");
$nom = htmlentities($_POST['nom'], ENT_QUOTES, "UTF-8");
$login = htmlentities($_POST['login'], ENT_QUOTES, "UTF-8");
$mdp = htmlentities($_POST['mdp'], ENT_QUOTES, "UTF-8");
$verifMdp = htmlentities($_POST['verifmdp'], ENT_QUOTES, "UTF-8");
$tel = htmlentities($_POST['tel'], ENT_QUOTES, "UTF-8");
// Récupérer la valeur du statut
$statut = isset($_POST['statut']) ? $_POST['statut'] : null;

// Récupérer la valeur de la date de naissance
$dateNaissance = isset($_POST['date']) ? $_POST['date'] : null;

$connexion = mysqli_connect($_serverBDD, $_userBDD, $_mdpBDD, $_nomBDD);
if(!$connexion){
    die("Error Connexion");
}

if ($mdp == $verifMdp){

    $hashedMdp = htmlentities(string: md5($mdp));

    $stmt = $connexion->prepare("INSERT INTO users(nom, prenom, dateN, mail, `login`, mdp, tel, id_statut) VALUES( ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt -> bind_param("ssdssssi", $nom, $login, $dateNaissance, $email, $login,  $hashedMdp, $tel, $statut);
    $stmt->execute();
    $note_result = $stmt->get_result(); // Récupère le résultat
}







?>