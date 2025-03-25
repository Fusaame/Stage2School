<?php
include 'conf.php';
include 'navbarProf.php';

session_start();

$connexion = mysqli_connect($_serverBDD, $_userBDD, $_mdpBDD, $_nomBDD);

if (!$connexion) {
    die("Erreur de connexion à la base de données.");
}

// Vérifie si un ID est passé en paramètre GET
if (isset($_GET['id'])) {
    $idCR = $_GET['id'];

    // Prépare et exécute la requête pour récupérer le compte rendu
    $stmt = $connexion->prepare("SELECT CR.id, CR.sujet, CR.contenu, CR.date_creation, CR.date_modif, CR.note, CR.vu FROM CR WHERE id = ?");
    $stmt->bind_param("i", $idCR);
    $stmt->execute();
    $CR_result = $stmt->get_result();
    $CR_data = $CR_result->fetch_assoc();

    // Met à jour la colonne "vu" à 1 si ce n'est pas déjà fait
    if ($CR_data && $CR_data['vu'] == 0) {
        $update_stmt = $connexion->prepare("UPDATE CR SET vu = 1 WHERE id = ?");
        $update_stmt->bind_param("i", $idCR);
        $update_stmt->execute();
    }
} else {
    echo "Aucun compte rendu sélectionné.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consultation du Compte Rendu</title>
    <link rel="stylesheet" href="consulter.css"> <!-- Inclut le CSS avec les styles adaptés -->
</head>
<body>
    <div class="compte-rendu-container">
        <h1>Consultation du Compte Rendu</h1>

        <?php if ($CR_data): ?>
            <p><strong>Sujet :</strong> <?php echo htmlspecialchars($CR_data['sujet']); ?></p>
            <p><strong>Contenu :</strong> <?php echo nl2br(htmlspecialchars($CR_data['contenu'])); ?></p>
            <p><strong>Date de création :</strong> <?php echo date('d/m/Y', strtotime($CR_data['date_creation'])); ?></p>
            <p><strong>Dernière modification :</strong> <?php echo date('d/m/Y', strtotime($CR_data['date_modif'])); ?></p>
            <p><strong>Note /5 :</strong> <?php echo $CR_data['note']; ?></p>
            <p><strong>Vu :</strong> <?php echo $CR_data['vu'] ? 'Oui' : 'Non'; ?></p>
        <?php else: ?>
            <p>Aucun compte rendu trouvé pour l'ID fourni.</p>
        <?php endif; ?>

        <!-- Lien de retour -->
        <a href="list_cr_prof.php" class="back-link">Retour à la liste</a>
    </div>
</body>
</html>
