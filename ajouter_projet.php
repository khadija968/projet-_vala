<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un projet - VALA</title>
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
      --form-bg: #1E1E1E;
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
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      max-width: 600px;
      margin: 0 auto;
      color: var(--secondary);
    }

    .form-section h1 {
      font-size: 32px;
      margin-bottom: 30px;
      color: var(--accent);
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--secondary);
    }

    input[type="text"],
    input[type="email"],
    textarea,
    select {
      width: 100%;
      padding: 12px 16px;
      background-color: var(--form-bg);
      border: 1px solid #444;
      border-radius: 4px;
      color: var(--secondary);
      font-family: 'Inter', sans-serif;
      font-size: 16px;
    }

    textarea {
      min-height: 120px;
      resize: vertical;
    }

    input:focus,
    textarea:focus,
    select:focus {
      outline: none;
      border-color: var(--accent);
      box-shadow: 0 0 0 2px rgba(8, 255, 136, 0.2);
    }

    .submit-btn {
      background-color: var(--accent);
      color: var(--primary);
      border: none;
      padding: 14px 28px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s ease;
      width: 100%;
      margin-top: 20px;
    }

    .submit-btn:hover {
      background-color: #06cc6a;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(8, 255, 136, 0.3);
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

    @media (max-width: 768px) {
      .form-section {
        padding: 30px 20px;
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
    <div class="form-section">
      <h1>➕ Ajouter un projet</h1>
      
      <?php
      // Afficher les messages de notification
      if (isset($_GET['success'])) {
        echo '<div class="notification success">✅ Projet ajouté avec succès</div>';
      } elseif (isset($_GET['error'])) {
        echo '<div class="notification error">❌ Erreur: '.htmlspecialchars($_GET['error']).'</div>';
      }
      ?>
      
      <form action="enregistrer_projet.php" method="post">
        <div class="form-group">
          <label for="titre">Titre du projet :</label>
          <input type="text" id="titre" name="titre" required>
        </div>
        
        <div class="form-group">
          <label for="client">Client :</label>
          <input type="text" id="client" name="client" required>
        </div>
        
        <div class="form-group">
          <label for="contact_email">Email de contact :</label>
          <input type="email" id="contact_email" name="contact_email" required>
        </div>
        
        <div class="form-group">
          <label for="description">Description :</label>
          <textarea id="description" name="description" required></textarea>
        </div>
        
        <div class="form-group">
          <label for="statut">Statut :</label>
          <select id="statut" name="statut" required>
            <option value="en attente">En attente</option>
            <option value="en cours">En cours</option>
            <option value="terminé">Terminé</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="date_debut">Date de début :</label>
          <input type="date" id="date_debut" name="date_debut" required>
        </div>
        
        <div class="form-group">
          <label for="date_fin_prevue">Date de fin prévue :</label>
          <input type="date" id="date_fin_prevue" name="date_fin_prevue">
        </div>
        
        <button type="submit" class="submit-btn">Ajouter le projet</button>
      </form>
    </div>
  </main>

  <footer>
    <p>© <?= date('Y') ?> VALA - Creative Internet Solutions. Tous droits réservés.</p>
  </footer>

</body>
</html>