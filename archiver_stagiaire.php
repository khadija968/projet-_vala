<?php
session_start();

// Solution temporaire - À supprimer après test
$_SESSION['admin_id'] = 1;

// Vérification de la session
if (!isset($_SESSION['admin_id'])) {
    die("Accès non autorisé");
}

// Configuration de la base de données
$db_config = [
    'host' => 'localhost',
    'db' => 'admin2',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4'
];

try {
    // Connexion PDO améliorée
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['db']};charset={$db_config['charset']}";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, $db_config['user'], $db_config['pass'], $options);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérification de l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: stagiaires.php?erreur=id_invalide');
    exit();
}

$id = (int)$_GET['id'];

try {
    // Vérification de l'existence du stagiaire
    $check = $pdo->prepare("SELECT id, nom FROM stagiaires WHERE id = ?");
    $check->execute([$id]);
    
    if ($check->rowCount() === 0) {
        header('Location: stagiaires.php?erreur=stagiaire_introuvable');
        exit();
    }

    $stagiaire = $check->fetch();

    // Désactiver temporairement les contraintes de clé étrangère si nécessaire
    // $pdo->exec("SET FOREIGN_KEY_CHECKS=0");

    // Archivage du stagiaire
    $update = $pdo->prepare("UPDATE stagiaires SET 
                            statut = 'archivé',
                            date_archivage = NOW(),
                            archive_par = ?
                            WHERE id = ?");
    
    $update->execute([$_SESSION['admin_id'], $id]);

    // Réactiver les contraintes
    // $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    // Debug: Vérifier si la mise à jour a fonctionné
    if ($update->rowCount() > 0) {
        // Journalisation (optionnelle)
        try {
            $log = $pdo->prepare("INSERT INTO logs_admin 
                                (admin_id, action, details) 
                                VALUES (?, ?, ?)");
            $log->execute([
                $_SESSION['admin_id'],
                'archivage_stagiaire',
                "Archivage du stagiaire #$id - " . $stagiaire['nom']
            ]);
        } catch (Exception $e) {
            error_log("Erreur de journalisation: " . $e->getMessage());
        }

        header('Location: stagiaires.php?archive=success&id='.$id);
        exit();
    } else {
        header('Location: stagiaires.php?erreur=aucune_modification');
        exit();
    }

} catch (PDOException $e) {
    // Debug: Afficher l'erreur SQL complète (à supprimer en production)
    die("Erreur SQL: " . $e->getMessage() . 
        "<br>Code erreur: " . $e->getCode() . 
        "<br>Requête: " . $update->queryString);
}