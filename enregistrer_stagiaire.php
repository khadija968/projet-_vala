<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=admin2;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    header("Location: ajouter_stagiaire.php?error=Erreur%20de%20connexion%20BDD");
    exit;
}

// Vérification des champs obligatoires
$requiredFields = ['nom', 'ecole', 'niveau', 'domaine', 'date_debut', 'date_fin'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header("Location: ajouter_stagiaire.php?error=Le%20champ%20".urlencode($field)."%20est%20requis");
        exit;
    }
}

try {
    // Validation des dates
    $dateDebut = new DateTime($_POST['date_debut']);
    $dateFin = new DateTime($_POST['date_fin']);

    if ($dateFin < $dateDebut) {
        header("Location: ajouter_stagiaire.php?error=La%20date%20de%20fin%20doit%20être%20postérieure%20à%20la%20date%20de%20début");
        exit;
    }

    // Préparation de la requête d'insertion
    $stmt = $pdo->prepare("INSERT INTO stagiaires 
        (nom, ecole, niveau, domaine, date_debut, date_fin) 
        VALUES (:nom, :ecole, :niveau, :domaine, :date_debut, :date_fin)");

    // Exécution avec les paramètres
    $stmt->execute([
        ':nom' => htmlspecialchars($_POST['nom']),
        ':ecole' => htmlspecialchars($_POST['ecole']),
        ':niveau' => htmlspecialchars($_POST['niveau']),
        ':domaine' => htmlspecialchars($_POST['domaine']),
        ':date_debut' => $dateDebut->format('Y-m-d'),
        ':date_fin' => $dateFin->format('Y-m-d')
    ]);

    // Redirection vers la liste des stagiaires
    header("Location: stagiaires.php?ajout=success");
    exit;

} catch (PDOException $e) {
    header("Location: ajouter_stagiaire.php?error=Erreur%20d'enregistrement");
    exit;
} catch (Exception $e) {
    header("Location: ajouter_stagiaire.php?error=Format%20de%20date%20invalide");
    exit;
}