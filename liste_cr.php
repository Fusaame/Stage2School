<?php 

// Set up pour afficher toute les erreurs avec les détails
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'conf.php';
include 'navbar.php';

session_start();

if(!isset($_SESSION['login'])){
    // Je renvoie la personne au login si elle n'est pas connectée
    header('location: index.php');
    exit;
}

$connexion = mysqli_connect($_serverBDD, $_userBDD, $_mdpBDD, $_nomBDD);

if (!$connexion) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

$nameEleve = $_SESSION['login'];

// on récupère les informations des CR de l'élève
$requete = "
    SELECT CR.id, CR.sujet, CR.contenu, CR.note, CR.date_creation, CR.date_modif, CR.vu
    FROM CR 
    INNER JOIN users ON CR.id_user = users.id
    WHERE users.login = '$nameEleve'
    ORDER BY CR.date_creation DESC
";

$result = mysqli_query($connexion, $requete);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_cr'])){

    $cr_id = intval($_POST['cr_id']); 

    $delete = "DELETE FROM CR WHERE id = $cr_id";

    if(mysqli_query($connexion, $delete)){
        $message = "Compte rendu supprimé avec succès.";
        header('Location: liste_cr.php');
    } else {
        $message = "Erreur".mysqli_error($connexion);
    }

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cr'])) {
    $cr_id = $_POST['cr_id'];
    $sujet = mysqli_real_escape_string($connexion, $_POST['sujet']);
    $contenu = mysqli_real_escape_string($connexion, $_POST['contenu']);
    $note = intval($_POST['note']);

    // On upadte le compte-rendu
    $updateQuery = "
        UPDATE CR 
        SET sujet = '$sujet', contenu = '$contenu',note = $note, date_modif = NOW() 
        WHERE id = $cr_id
    ";

    if (mysqli_query($connexion, $updateQuery)) {
        $message = "Compte rendu mis à jour avec succès.";
        $updateQuery2 = "
        UPDATE CR 
        SET vu = 0
        WHERE id = $cr_id
        ";
        mysqli_query($connexion, $updateQuery2);
        header('Location: liste_cr.php');
    } else {
        $message = "Erreur lors de la mise à jour : " . mysqli_error($connexion);
    }
}
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
    </style>
</head>
<body>
    
    <h1>Vos Comptes Rendus</h1>

    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <table class="compte-rendu-table">
        <thead>
            <tr class="subject">
                <th>Sujet</th>
                <th>Contenu</th>
                <th>Date de création</th>
                <th>Dernière modification</th>
                <th>Note /5</th>
                <th>Vu</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($cr = mysqli_fetch_assoc($result)): 
                    if ($cr['note'] == null){
                        $note = 6;
                    } else {
                        $note = $cr['note'];
                    }
                    
                    ?>
                    <?php $varVu = "";

                    $vu = $cr['vu'];
                    
                    if ($vu == 1){
                        $varVu = "Oui";
                    } else {
                        $varVu = "Non";
                    }
                    
                    
                    ?>
                    <tr class="result">
                        <td><?php echo $cr['sujet']; ?></td>
                        <td><?php echo $cr['contenu']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cr['date_creation'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($cr['date_modif'])); ?></td>
                        <td><?php echo $cr['note']; ?></td>
                        <td><?php echo $varVu ?></td>
                        <td>
                            <!-- bouton modifi er -->
                            <button onclick="document.getElementById('edit-form-<?php echo $cr['id']; ?>').style.display='block'">Modifier</button>
                            <!-- bouton supprimer -->
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="cr_id" value="<?php echo $cr['id']; ?>">
                                <button type="submit" class="note-rouge" name="delete_cr" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte rendu ?');">X</button>
                            </form>
                        </td>
                    </tr>
                    <!-- formulaire pour editer le sujet et/ou le contenu  // Le display:none est la pour cacher ce qu'il y a après modifier -->
                    <tr id="edit-form-<?php echo $cr['id']; ?>" style="display:none;">
                        <!-- colspan pour unifier les colonnes -->
                        <td colspan="6">
                            <!-- Formulaire pour modifer le CR -->
                            <form method="POST" action="">
                                <input type="hidden" name="cr_id" value="<?php echo $cr['id']; ?>">
                                <label for="sujet-<?php echo $cr['id']; ?>">Sujet:</label>
                                <input type="text" id="sujet-<?php echo $cr['id']; ?>" name="sujet" value="<?php echo htmlspecialchars($cr['sujet']); ?>" required><br><br>

                                <label for="contenu-<?php echo $cr['id']; ?>">Contenu:</label>
                                <textarea id="contenu-<?php echo $cr['id']; ?>" name="contenu" required><?php echo htmlspecialchars($cr['contenu']); ?></textarea><br><br>
                                <!-- Modifier la note -->
                                <label for="note-<?php echo $cr['id']; ?>">Note:</label>
                                <select id="note-<?php echo $cr['id']; ?>" name="note" required>
                                    <option value="null" <?php echo ($note == 6) ? 'selected' : ''; ?>>Non renseigné</option>
                                    <option value="0" <?php echo ($note == 0) ? 'selected' : ''; ?>>0</option>
                                    <option value="1" <?php echo ($note == 1) ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo ($note == 2) ? 'selected' : ''; ?>>2</option>
                                    <option value="3" <?php echo ($note == 3) ? 'selected' : ''; ?>>3</option>
                                    <option value="4" <?php echo ($note == 4) ? 'selected' : ''; ?>>4</option>
                                    <option value="5" <?php echo ($note == 5) ? 'selected' : ''; ?>>5</option>
                                </select>
                                <button type="submit" name="update_cr">Enregistrer les modifications</button>
                                <button type="button" onclick="document.getElementById('edit-form-<?php echo $cr['id']; ?>').style.display='none'">Annuler</button>
                            </form>
                        </td>
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
