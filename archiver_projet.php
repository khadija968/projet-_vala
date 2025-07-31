<?php
// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "admin2");

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si l'identifiant du projet est passé dans l'URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Requête pour archiver le projet (modifier le champ "statut")
    $update = $conn->prepare("UPDATE projets SET statut = 'archivé' WHERE id = ?");
    $update->bind_param("i", $id);

    if ($update->execute()) {
        // Redirection avec succès
        header("Location: projets.php?archive=success");
        exit;
    } else {
        // Redirection avec erreur
        header("Location: projets.php?erreur=erreur_maj");
        exit;
    }
} else {
    // Si aucun identifiant n’est fourni
    header("Location: projets.php?erreur=id_manquant");
    exit;
}
?>
