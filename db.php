<?php
/**
 * Connexion à la base de données avec PDO
 * 
 * Ce script établit une connexion sécurisée à la base de données MySQL
 * en utilisant PDO avec gestion des erreurs et configuration recommandée.
 * 
 * Modifié pour utiliser la base de données 'admin2'.
 */

// Paramètres de connexion à la base de données
define('DB_HOST', 'localhost');      // Adresse du serveur MySQL
define('DB_NAME', 'admin2');         // Nom de la base de données modifié ici
define('DB_USER', 'root');           // Nom d'utilisateur MySQL
define('DB_PASS', '');               // Mot de passe (vide par défaut en local)

try {
    // Création d'une nouvelle instance PDO pour la connexion
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", // DSN (Data Source Name)
        DB_USER,          // Utilisateur
        DB_PASS,          // Mot de passe
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           // Active les exceptions en cas d'erreur SQL
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Récupération des résultats sous forme de tableau associatif
            PDO::ATTR_EMULATE_PREPARES => false                     // Utilisation des requêtes préparées natives du serveur
        ]
    );

    // Optionnel : message de confirmation de connexion réussie (à décommenter en développement)
    // echo "✅ Connexion à la base de données 'admin2' établie avec succès.";
    
} catch (PDOException $e) {
    // En cas d'erreur de connexion, on log l'erreur et on affiche un message utilisateur clair

    // Enregistrer l'erreur dans le fichier log PHP (pour débogage développeur)
    error_log("Échec de connexion à la base de données : " . $e->getMessage());

    // Afficher un message convivial à l'utilisateur final
    die(
        "<div class='p-4 bg-red-100 text-red-700 rounded-lg'>
            <h3 class='font-bold'>Erreur de connexion</h3>
            <p>Impossible de se connecter à la base de données. Veuillez réessayer plus tard.</p>
            <p class='text-sm mt-2'>" . htmlspecialchars($e->getMessage()) . "</p>
        </div>"
    );
}
?>
