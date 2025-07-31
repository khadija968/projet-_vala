<?php
session_start();

// Connexion PDO à la base admin2
try {
    $pdo = new PDO('mysql:host=localhost;dbname=admin2;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des valeurs depuis l'URL (si présentes)
$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
$cible_id = isset($_GET['id']) ? (int) $_GET['id'] : '';

// Traitement de l'envoi du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le nom d'admin depuis la session, ou mettre 'admin' par défaut
    $admin = $_SESSION['admin_username'] ?? 'admin';

    // Nettoyage / sécurisation des entrées
    $sujet = trim($_POST['sujet'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $cible_type = trim($_POST['cible_type'] ?? '');
    $cible_id = (int)($_POST['cible_id'] ?? 0);

    if (!empty($sujet) && !empty($message) && !empty($cible_type) && $cible_id > 0) {
        // Préparation et insertion dans la table support_messages
        $stmt = $pdo->prepare("INSERT INTO support_messages (admin_username, sujet, message, cible_type, cible_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$admin, $sujet, $message, $cible_type, $cible_id]);

        $confirmation = "✅ Message envoyé avec succès !";
    } else {
        $confirmation = "❌ Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Support interne - VALA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #000000;
            --secondary: #FFFFFF;
            --accent: #08FF88;
            --background: #FFFFFF;
            --text: #333333;
            --dark-bg: #111111;
        }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background);
            color: var(--primary);
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
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
            font-weight: normal;
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
        .box {
            background: var(--dark-bg);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            color: var(--secondary);
            max-width: 800px;
            margin: 40px auto;
        }
        h2 {
            color: var(--accent);
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: 700;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary);
        }
        input, textarea, select {
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 20px;
            border: 2px solid #333;
            border-radius: 8px;
            background-color: #222;
            color: var(--secondary);
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(8, 255, 136, 0.2);
        }
        textarea {
            min-height: 150px;
            resize: vertical;
        }
        button {
            background-color: var(--accent);
            color: var(--primary);
            border: none;
            padding: 14px 28px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        button:hover {
            background-color: #06cc6a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(8, 255, 136, 0.3);
        }
        p {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        footer {
            background-color: var(--primary);
            color: var(--secondary);
            padding: 40px 5%;
            text-align: center;
            margin-top: 50px;
        }
        @media (max-width: 768px) {
            .box {
                padding: 30px 20px;
            }
            h2 {
                font-size: 24px;
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

    <div class="container">
        <div class="box">
            <h2>📩 Contacter le support interne</h2>
            <?php
                if (isset($confirmation)) {
                    $bgColor = $confirmation[0] === '✅' ? '#1a3a1a' : '#3a1a1a';
                    echo '<p style="background-color:' . $bgColor . ';">' . htmlspecialchars($confirmation) . '</p>';
                }
            ?>
            <form method="post" action="">
                <label for="sujet">Sujet :</label>
                <input type="text" id="sujet" name="sujet" required placeholder="Quel est l'objet de votre message ?" value="<?= isset($sujet) ? htmlspecialchars($sujet) : '' ?>">

                <label for="message">Message :</label>
                <textarea id="message" name="message" required placeholder="Décrivez votre demande en détails..."><?= isset($message) ? htmlspecialchars($message) : '' ?></textarea>

                <label for="cible_type">Type de cible :</label>
                <select id="cible_type" name="cible_type" required>
                    <option value="">-- Choisir --</option>
                    <option value="projet" <?= $type === 'projet' ? 'selected' : '' ?>>Projet</option>
                    <option value="stagiaire" <?= $type === 'stagiaire' ? 'selected' : '' ?>>Stagiaire</option>
                </select>

                <label for="cible_id">ID de la cible :</label>
                <input type="number" id="cible_id" name="cible_id" required min="1" value="<?= htmlspecialchars($cible_id) ?>" placeholder="ID de la cible">

                <button type="submit">Envoyer le message</button>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2023 VALA - Creative Internet Solutions. Tous droits réservés.</p>
    </footer>
</body>
</html>