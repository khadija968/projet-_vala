<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter un stagiaire - VALA</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #000000; /* Noir pur pour texte */
      --secondary: #FFFFFF; /* Blanc */
      --accent: #08FF88; /* Vert */
      --background: #FFFFFF; /* Fond blanc pour la page */
      --text: #333333; /* Texte principal */
      --dark-bg: #111111; /* Fond noir pour sections */
      --nav-text: #000000; /* Noir pur pour navigation */
      --form-bg: #1E1E1E; /* Fond du formulaire */
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
    input[type="date"],
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

    input:focus {
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
      <h1>Ajouter un stagiaire</h1>
      
      <form action="enregistrer_stagiaire.php" method="post">
        <div class="form-group">
          <label for="nom">Nom :</label>
          <input type="text" id="nom" name="nom" required>
        </div>
        
        <div class="form-group">
          <label for="ecole">École :</label>
          <input type="text" id="ecole" name="ecole" required>
        </div>
        
        <div class="form-group">
          <label for="niveau">Niveau :</label>
          <input type="text" id="niveau" name="niveau" required>
        </div>
        
        <div class="form-group">
          <label for="domaine">Domaine :</label>
          <input type="text" id="domaine" name="domaine" required>
        </div>
        
        <div class="form-group">
          <label for="date_debut">Date de début :</label>
          <input type="date" id="date_debut" name="date_debut" required>
        </div>
        
        <div class="form-group">
          <label for="date_fin">Date de fin :</label>
          <input type="date" id="date_fin" name="date_fin" required>
        </div>
        
        <button type="submit" class="submit-btn">Ajouter le stagiaire</button>
      </form>
    </div>
  </main>

  <footer>
    <p>© 2023 VALA - Creative Internet Solutions. Tous droits réservés.</p>
  </footer>

</body>
</html>