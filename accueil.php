<?php
// Connexion à la base de données admin2
try {
    $pdo = new PDO('mysql:host=localhost;dbname=admin2;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Nombre de projets en cours (statut = 'en cours')
$nb_projets = $pdo->query("SELECT COUNT(*) FROM projets WHERE statut = 'en cours'")->fetchColumn();

// Nombre de stagiaires non archivés
$nb_stagiaires = $pdo->query("SELECT COUNT(*) FROM stagiaires WHERE statut != 'archivé'")->fetchColumn();

// Nombre de messages internes non archivés (version corrigée)
$nb_messages = $pdo->query("
    SELECT COUNT(*) 
    FROM messages m
    LEFT JOIN projets p ON m.projet_id = p.id
    WHERE (p.id IS NULL OR p.statut != 'archivé')
")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>VALA - Creative Internet Solutions</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #000000;
      --secondary: #FFFFFF;
      --accent: #08FF88;
      --marine: #001f3f;
      --text: #333333;
      --dark-bg: #111111;
      --card-bg: #000000;
    }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      color: var(--text);
      background-color: var(--secondary);
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 5%;
      background-color: var(--secondary);
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .logo {
      font-weight: bold;
      font-size: 24px;
      color: var(--primary);
    }

    .logo span {
      display: block;
      font-size: 14px;
      color: #666;
    }

    nav a {
      margin-left: 20px;
      text-decoration: none;
      color: var(--primary);
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

    .hero {
      position: relative;
      height: 80vh;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      background-color: black;
    }

    .hero h1 {
      color: white;
      font-size: 42px;
      font-weight: bold;
      margin-bottom: 0;
      z-index: 2;
    }

    .hero-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      filter: brightness(0.7);
      position: absolute;
      top: 0;
      left: 0;
      z-index: 1;
    }

    .dashboard-mini {
      text-align: center;
      padding: 60px 5%;
      background: var(--secondary);
      color: var(--text);
    }

    .dashboard-mini h2 {
      font-size: 36px;
      margin-bottom: 40px;
      color: var(--primary);
    }

    .stats-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 30px;
    }

    .stat-card {
      background: var(--card-bg);
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      font-size: 18px;
      font-weight: 500;
      width: 100%;
      max-width: 400px;
      color: var(--secondary);
      position: relative;
    }

    .stat-card strong {
      color: var(--accent);
    }

    .stat-card::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      height: 5px;
      width: 100%;
      background: var(--accent);
      border-radius: 0 0 10px 10px;
    }

    footer {
      background-color: var(--dark-bg);
      color: var(--secondary);
      padding: 60px 5%;
      text-align: center;
    }

    @media (max-width: 768px) {
      .stat-card {
        padding: 20px 30px;
      }
      .dashboard-mini h2 {
        font-size: 28px;
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

<section class="hero">
  <h1>Créons ensemble l'avenir numérique</h1>
  <img src="B19.JPEG" alt="Image VALA" class="hero-image" />
</section>

<section class="dashboard-mini">
  <h2>Aperçu rapide</h2>
  <div class="stats-box">
    <div class="stat-card">📁 Projets en cours : <strong><?= htmlspecialchars($nb_projets) ?></strong></div>
    <div class="stat-card">👩‍🎓 Stagiaires actifs : <strong><?= htmlspecialchars($nb_stagiaires) ?></strong></div>
    <div class="stat-card">📨 Messages actifs : <strong><?= htmlspecialchars($nb_messages) ?></strong></div>
  </div>
</section>

<footer>
  <p>© <?= date('Y') ?> VALA - Creative Internet Solutions. Tous droits réservés.</p>
</footer>

</body>
</html>