<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=admin2;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Gestion des messages
$message = '';
if (isset($_GET['archive']) && $_GET['archive'] === 'success') {
    $message = '<div class="notification success">✅ Stagiaire archivé avec succès</div>';
} elseif (isset($_GET['erreur'])) {
    $message = '<div class="notification error">❌ Erreur: ' . htmlspecialchars($_GET['erreur']) . '</div>';
}

// Récupération des stagiaires non archivés
$query = "SELECT * FROM stagiaires WHERE statut != 'archivé' OR statut IS NULL";
$stmt = $pdo->query($query);

if (!$stmt) {
    die("Erreur lors de la récupération des stagiaires : " . print_r($pdo->errorInfo(), true));
}

$stagiaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des stagiaires avec notifications
$notifiedStagiaires = [];
$notifQuery = $pdo->query("SELECT DISTINCT cible_id FROM support_messages WHERE cible_type = 'stagiaire'");
if ($notifQuery) {
    $notifiedStagiaires = $notifQuery->fetchAll(PDO::FETCH_COLUMN, 0);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stagiaires - VALA</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #000000;
      --secondary: #FFFFFF;
      --accent: #08FF88;
      --background: #FFFFFF;
      --text: #333333;
      --dark-bg: #111111;
      --nav-text: #000000;
    }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background-color: var(--background);
      color: var(--text);
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 5%;
      background-color: var(--secondary);
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .logo {
      font-weight: bold;
      font-size: 24px;
      color: var(--primary);
    }

    .logo span {
      display: block;
      font-size: 14px;
      font-weight: normal;
      color: #666;
    }

    nav a {
      margin-left: 20px;
      text-decoration: none;
      color: var(--nav-text);
      font-weight: 500;
      position: relative;
      padding-bottom: 5px;
    }

    nav a:hover::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 2px;
      bottom: 0;
      left: 0;
      background-color: var(--accent);
    }

    .add-button {
      background-color: var(--accent);
      color: var(--primary);
      border: none;
      padding: 12px 24px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 20px;
    }

    .add-button:hover {
      background-color: #06cc6a;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(8, 255, 136, 0.3);
    }

    main {
      padding: 40px 5%;
      background-color: var(--background);
    }

    .table-section {
      background-color: var(--dark-bg);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      margin-top: 20px;
    }

    .table-section h1 {
      font-size: 32px;
      margin-bottom: 30px;
      color: var(--accent);
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #000;
      border: 2px solid #fff;
      border-radius: 8px;
      overflow: hidden;
      color: #eee;
    }

    th, td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #444;
    }

    th {
      background-color: #222;
      font-weight: 600;
      color: #fff;
    }

    tr:hover {
      background-color: #1a1a1a;
    }

    a.action-link {
      margin-right: 12px;
      text-decoration: none;
      color: var(--accent);
      font-weight: 500;
    }

    a.action-link:hover {
      color: #06cc6a;
    }

    .notification {
      padding: 12px 16px;
      border-radius: 6px;
      margin-bottom: 20px;
      font-weight: 500;
      text-align: center;
    }

    .notification.success {
      background-color: #1a3a1a;
      color: var(--accent);
    }

    .notification.error {
      background-color: #3a1a1a;
      color: #ff6b6b;
    }

    footer {
      background-color: var(--dark-bg);
      color: var(--secondary);
      padding: 40px 5%;
      text-align: center;
      margin-top: 50px;
    }

    /* Styles pour la modale d'idées */
    #idea-modal {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: var(--dark-bg);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.5);
      z-index: 1000;
      width: 80%;
      max-width: 600px;
      border: 2px solid var(--accent);
      color: white;
    }

    #idea-content {
      font-size: 18px;
      margin: 20px 0;
      color: var(--secondary);
      line-height: 1.6;
    }

    .modal-actions {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
    }

    @media (max-width: 768px) {
      table, thead, tbody, th, td, tr {
        display: block;
      }

      th {
        display: none;
      }

      td {
        padding: 12px;
        border: none;
        border-bottom: 1px solid #444;
        position: relative;
      }

      td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 6px;
        color: #999;
      }

      #idea-modal {
        width: 90%;
        padding: 20px;
      }
    }
    .logout-btn {
    margin-left: 20px;
    padding: 8px 16px;
    background-color: var(--accent);
    color: var(--primary);
    border: none;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
}

.logout-btn:hover {
    background-color: #06d673;
    transform: translateY(-2px);
}
  </style>
</head>
<body>

  <header>
    <div class="logo">
      VALA
      <span>Creative Internet Solutions</span>
    </div>
    <nav>
      <a href="accueil.php">Accueil</a>
      <a href="services.html">Services</a>
      <a href="projets.php">Projets</a>
      <a href="stagiaires.php">Stagiaires</a>
      <a href="support.php">Support interne</a>
      <a href="logout.php" class="logout-btn">Déconnexion</a>
    </nav>
  </header>

  <main>
    <?php echo $message; ?>

    <div style="text-align: right; display: flex; justify-content: flex-end; gap: 10px;">
      <a href="ajouter_stagiaire.php" class="add-button">+ Ajouter un stagiaire</a>
      <button onclick="generateIdea()" class="add-button">💡 Générer une idée</button>
    </div>
    
    <div class="table-section">
      <h1>Liste des Stagiaires</h1>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>École</th>
            <th>Domaine</th>
            <th>Période</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($stagiaires) > 0): ?>
            <?php foreach ($stagiaires as $stagiaire): ?>
            <tr>
              <td data-label="ID"><?= htmlspecialchars($stagiaire['id'] ?? '') ?></td>
              <td data-label="Nom"><?= htmlspecialchars($stagiaire['nom'] ?? '') ?></td>
              <td data-label="École"><?= htmlspecialchars($stagiaire['ecole'] ?? '') ?></td>
              <td data-label="Domaine"><?= htmlspecialchars($stagiaire['domaine'] ?? '') ?></td>
              <td data-label="Période">
                <?= htmlspecialchars($stagiaire['date_debut'] ?? '') ?> → <?= htmlspecialchars($stagiaire['date_fin'] ?? '') ?>
              </td>
              <td data-label="Actions">
                <a href="suivi.php?id=<?= $stagiaire['id'] ?>" class="action-link">Suivre</a>
                <?php if (in_array($stagiaire['id'], $notifiedStagiaires)): ?>
                  <a href="historique_support.php?type=stagiaire&id=<?= $stagiaire['id'] ?>" class="action-link">🔔</a>
                <?php endif; ?>
                <a href="archiver_stagiaire.php?id=<?= $stagiaire['id'] ?>" class="action-link" style="color: red;">Archiver</a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align: center;">Aucun stagiaire trouvé</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modale pour afficher les idées générées -->
  <div id="idea-modal">
    <h3 style="color: var(--accent); margin-top: 0;">✨ Idée Générée</h3>
    <div id="idea-content"></div>
    <div class="modal-actions">
      <button onclick="document.getElementById('idea-modal').style.display='none'" class="add-button" style="background: #ff6b6b;">Fermer</button>
      <button onclick="generateIdea()" class="add-button">Nouvelle idée</button>
    </div>
  </div>

  <footer>
    <p>© <?= date('Y') ?> VALA - Creative Internet Solutions. Tous droits réservés.</p>
  </footer>

  <script>
  // Liste d'idées prédéfinies adaptées au développement web
  const ideas = [
    "Développer un module React pour le dashboard client",
    "Créer une API REST pour le suivi des projets",
    "Optimiser les requêtes SQL de la base de données",
    "Implémenter JWT pour l'authentification",
    "Refactorer le module de paiement legacy",
    "Créer des composants UI réutilisables en Storybook",
    "Mettre en place des tests unitaires avec PHPUnit",
    "Développer un système de notifications en temps réel",
    "Configurer CI/CD avec GitHub Actions",
    "Améliorer l'accessibilité WCAG du site"
  ];

  function generateIdea() {
    // Sélection aléatoire
    const randomIdea = ideas[Math.floor(Math.random() * ideas.length)];
    const projectCode = "PROJ-" + Math.random().toString(36).substring(2, 10).toUpperCase();
    
    // Affichage
    document.getElementById('idea-content').textContent = `${randomIdea} (${projectCode})`;
    document.getElementById('idea-modal').style.display = 'block';
  }
  </script>
</body>
</html>