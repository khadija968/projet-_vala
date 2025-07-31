<?php
require 'db.php';

$confirmation = '';
$id = $_GET['id'] ?? null;

// Vérifie si l'ID est bien présent
if (!$id) {
    die("ID du stagiaire manquant.");
}

// Récupération du stagiaire
$stmt = $pdo->prepare("SELECT * FROM stagiaires WHERE id = ?");
$stmt->execute([$id]);
$stagiaire = $stmt->fetch();

// Si formulaire soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $travail = $_POST["travail"];
    $progression = $_POST["progression"];
    $commentaire = $_POST["commentaire"];

    $stmt = $pdo->prepare("INSERT INTO suivis (stagiaire_id, travail, progression, commentaire)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $travail, $progression, $commentaire]);

    $confirmation = "✅ Suivi enregistré avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Suivi - <?= htmlspecialchars($stagiaire['nom']) ?> - VALA</title>
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

    main {
      padding: 40px 5%;
      background-color: var(--background);
    }

    .form-section {
      background-color: var(--dark-bg);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      margin-top: 20px;
      color: white;
    }

    .form-section h1 {
      font-size: 28px;
      margin-bottom: 20px;
      color: var(--accent);
      text-align: center;
    }

    label {
      display: block;
      margin: 15px 0 8px;
      font-weight: 500;
    }

    textarea, input[type="number"] {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: none;
      font-size: 16px;
      margin-bottom: 10px;
    }

    input[type="submit"] {
      background-color: var(--accent);
      color: var(--primary);
      padding: 12px 24px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 4px;
      border: none;
      cursor: pointer;
      transition: 0.3s ease;
    }

    input[type="submit"]:hover {
      background-color: #06cc6a;
      transform: translateY(-2px);
    }

    .confirmation-message {
      background-color: #d4edda;
      color: #155724;
      padding: 12px;
      margin-bottom: 20px;
      border-left: 6px solid #28a745;
      border-radius: 5px;
    }

    footer {
      background-color: var(--dark-bg);
      color: var(--secondary);
      padding: 40px 5%;
      text-align: center;
      margin-top: 50px;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 20px;
      text-decoration: none;
      color: var(--accent);
      font-weight: 500;
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
      <a href="accueil.html">Accueil</a>
      <a href="services.html">Services</a>
      <a href="projets.php">Projets</a>
      <a href="stagiaires.php">Stagiaires</a>
      <a href="support.php">Support interne</a>
      <a href="logout.php" class="logout-btn">Déconnexion</a>
    </nav>
  </header>

  <main>
    <a href="stagiaires.php" class="back-link">← Retour à la liste des stagiaires</a>

    <div class="form-section">
      <h1>Suivi du stagiaire : <?= htmlspecialchars($stagiaire['nom']) ?></h1>

      <?php if (!empty($confirmation)): ?>
        <div class="confirmation-message"><?= $confirmation ?></div>
      <?php endif; ?>

      <form action="" method="post">
        <label for="travail">Travail réalisé :</label>
        <textarea name="travail" id="travail" rows="5" required></textarea>

        <label for="progression">Pourcentage réalisé :</label>
        <input type="number" name="progression" id="progression" min="0" max="100" required>

        <label for="commentaire">Commentaire tuteur :</label>
        <textarea name="commentaire" id="commentaire" rows="3"></textarea>

        <input type="submit" value="Sauvegarder">
      </form>
    </div>
  </main>

  <footer>
    <p>© 2023 VALA - Creative Internet Solutions. Tous droits réservés.</p>
  </footer>

</body>
</html>
