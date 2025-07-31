<?php
session_start();

// Connexion à la base de données
$conn = new mysqli("localhost", "root", "", "admin2");
if ($conn->connect_error) {
    header("Location: ajouter_projet.php?error=Erreur%20de%20connexion%20BDD");
    exit;
}

// Vérification des champs obligatoires
$requiredFields = ['titre', 'contact_email', 'description', 'statut', 'date_debut'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        header("Location: ajouter_projet.php?error=Champ%20".urlencode($field)."%20requis");
        exit;
    }
}

// Validation des données
$titre = $conn->real_escape_string($_POST['titre']);
$email = filter_var($_POST['contact_email'], FILTER_VALIDATE_EMAIL);
$description = $conn->real_escape_string($_POST['description']);
$statut = in_array($_POST['statut'], ['en attente', 'en cours', 'terminé']) 
          ? $_POST['statut'] 
          : 'en attente';

// Validation des dates
$date_debut = date('Y-m-d', strtotime($_POST['date_debut']));
$date_fin = !empty($_POST['date_fin_prevue']) 
            ? date('Y-m-d', strtotime($_POST['date_fin_prevue'])) 
            : null;

if (!$date_debut) {
    header("Location: ajouter_projet.php?error=Date%20début%20invalide");
    exit;
}

// Requête d'insertion
$sql = "INSERT INTO projets (titre, contact_email, description, statut, date_debut, date_fin_prevue)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    header("Location: ajouter_projet.php?error=Erreur%20préparation%20requête");
    exit;
}

$stmt->bind_param("ssssss", $titre, $email, $description, $statut, $date_debut, $date_fin);

if ($stmt->execute()) {
    header("Location: projets.php?ajout=success");
} else {
    header("Location: ajouter_projet.php?error=Erreur%20d'enregistrement");
}

$stmt->close();
$conn->close();
exit;