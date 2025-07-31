<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=admin2;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;

if ($type && $id) {
    $stmt = $pdo->prepare("SELECT * FROM support_messages WHERE cible_type = ? AND cible_id = ? ORDER BY date_envoi DESC");
    $stmt->execute([$type, $id]);
    $messages = $stmt->fetchAll();
} else {
    $messages = $pdo->query("SELECT * FROM support_messages ORDER BY date_envoi DESC")->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Historique Support - VALA</title>
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

    .table-section {
      background-color: var(--dark-bg);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      margin-top: 20px;
    }

    .table-section h1 {
      font-size: 32px;
      margin-bottom: 20px;
      color: var(--accent);
      text-align: center;
    }

    .back-link {
      text-decoration: none;
      display: inline-block;
      margin-bottom: 15px;
      color: #08FF88;
      font-weight: 500;
    }

    .filter-info {
      color: #ccc;
      margin-bottom: 15px;
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

    footer {
      background-color: var(--dark-bg);
      color: var(--secondary);
      padding: 40px 5%;
      text-align: center;
      margin-top: 50px;
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
    <a href="stagiaires.php" class="back-link">← Retour</a>

    <div class="table-section">
      <h1>🗂️ Historique des messages</h1>

      <?php if ($type && $id): ?>
        <div class="filter-info">🔍 Filtré par <strong><?= htmlspecialchars($type) ?> #<?= htmlspecialchars($id) ?></strong></div>
      <?php endif; ?>

      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>Admin</th>
            <th>Sujet</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($messages as $m): ?>
          <tr>
            <td><?= htmlspecialchars($m['date_envoi']) ?></td>
            <td><?= htmlspecialchars($m['admin_username']) ?></td>
            <td><?= htmlspecialchars($m['sujet']) ?></td>
            <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>

  <footer>
    <p>© 2023 VALA - Creative Internet Solutions. Tous droits réservés.</p>
  </footer>

</body>
</html>
