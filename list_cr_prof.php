<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conf.php';
include 'navbarProf.php';
session_start();

$connexion = mysqli_connect($_serverBDD, $_userBDD, $_mdpBDD, $_nomBDD);

if (!$connexion) {
    die("Erreur de connexion à la base de données.");
}

$stmt = $connexion->prepare("SELECT users.nom, users.prenom, users.id, CR.id, CR.sujet, CR.contenu, CR.note, CR.date_creation, CR.date_modif, CR.vu, CR.id_user FROM CR INNER JOIN users ON users.id = CR.id_user ORDER BY CR.date_creation DESC");
$stmt -> execute();
$result = $stmt->get_result(); // Récupère le résultat
//$note_data = $note_result->fetch_assoc(); // Récupère la ligne correspondante

//if (mysqli_num_rows($result) > 0){
 //   while($crProf = mysqli_fetch_assoc($result)){

        //echo $crProf['sujet'];
        //echo $crProf['contenu'];
 //   }
//}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Comptes Rendus</title>
    <link rel="stylesheet" href="liste_cr_style.css">
    <style>
        /* Pour que le bg soit rouge */
        .note-rouge {
            background-color: red;
            text-align: center;
            color: white; 
        }
        /* Pour que le bg soit verte */
        .note-verte {
            background-color: green;
            text-align: center;
            color: white;
        }
        .note-grise {
            background-color: gray;
            text-align: center;
            color: white; 
        }
        .note-non {
            background-color: black;
        }
    </style>
</head>
<body>
    
    <h1>Liste des Comptes Rendus</h1>

    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <table class="compte-rendu-table">
        <thead>
            <tr class="subject">
                <th>Nom</th>
                <th>Prénom</th>
                <th>Sujet</th>
                <th>Contenu</th>
                <th>Date de création</th>
                <th>Dernière modification</th>
                <th>Note /5</th>
                <th>Vu</th>
                <th>Consulter & Info</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($crProf = mysqli_fetch_assoc($result)): ?>
                    <?php $varVu = "";

                    $vu = $crProf['vu'];
                    
                    if ($vu == 1){
                        $varVu = "Oui";
                    } else {
                        $varVu = "Non";
                    }
                    ?>
                    <tr class="result">
                        <td><?php echo $crProf['nom']; ?></td>
                        <td><?php echo $crProf['prenom']; ?></td>
                        <td><?php echo $crProf['sujet']; ?></td>
                        <td><?php echo $crProf['contenu']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($crProf['date_creation'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($crProf['date_modif'])); ?></td>
                        <!-- Determine le bcackground en fonction de la note -->
                        <?php 
                        $noteClass = ''; 
                        if ($crProf['note'] === 0) {// si note = 0 alors on met le style CSS pour la couleur rouge 
                            $noteClass = 'note-rouge';
                        } elseif ($crProf['note'] === 5) { // si note = 5 on met le style CSS pour le couleur verte
                            $noteClass = 'note-verte';
                        } elseif ($crProf['note'] >= 1 && $crProf['note'] <= 4) {
                            $noteClass = 'note-grise';
                        } else {
                            $noteClass = 'note-non';
                        }
                        ?>
                        <td class="<?php echo $noteClass; ?>">
                            <?php echo htmlspecialchars($crProf['note']); ?>
                        </td>
                        <td><?php echo $varVu; ?></td>
                        <td>
                            <!-- bouton modifier -->
                            <button onclick="window.location.href='consulter.php?id=<?php echo $crProf['id']; ?>'">Compte-Rendu</button>      
                            <button onclick="window.location.href='InfoEleve_Prof.php?id_user=<?php echo $crProf['id_user']; ?>'">Informations</button>                   
                        </td>
                    <!-- formulaire pour editer le sujet et/ou le contenu  // Le display:none est la pour cacher ce qu'il y a après modifier -->
                    <tr id="edit-form-<?php echo $crProf['id']; ?>" style="display:none;">
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="no-result">Aucun compte rendu trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>