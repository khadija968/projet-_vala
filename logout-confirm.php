<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Déconnexion - VALA</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #000000;
      --secondary: #FFFFFF;
      --accent: #08FF88;
      --text: #333333;
    }

    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background-color: var(--secondary);
      color: var(--text);
      text-align: center;
    }

    .message-container {
      max-width: 500px;
      padding: 20px;
    }

    h1 {
      color: var(--primary);
      margin-bottom: 20px;
    }

    p {
      font-size: 18px;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
  <div class="message-container">
    <h1>Vous êtes déconnecté</h1>
    <p>Vous avez été déconnecté avec succès du système VALA.</p>
  </div>
</body>
</html>