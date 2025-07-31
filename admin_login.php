<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=admin2;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Modification ici : la table est 'admin', pas 'admin2'
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: accueil.php");
        exit();
    } else {
        $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Connexion Admin - VALA</title>
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
            background-color: var(--background);
            color: var(--text);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .logo {
            font-weight: bold;
            font-size: 32px;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .logo span {
            display: block;
            font-size: 16px;
            font-weight: normal;
            color: #666;
            text-align: center;
        }

        h2 {
            color: var(--secondary);
            margin-bottom: 30px;
            font-size: 24px;
        }

        .login-container {
            background-color: var(--dark-bg);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            width: 380px;
            max-width: 90%;
            border: 1px solid #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--secondary);
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #333;
            border-radius: 6px;
            font-size: 16px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background-color: #222;
            color: var(--secondary);
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(8, 255, 136, 0.2);
        }

        .login-button {
            width: 100%;
            padding: 14px;
            background-color: var(--accent);
            border: none;
            border-radius: 6px;
            color: var(--primary);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            font-family: 'Inter', sans-serif;
        }

        .login-button:hover {
            background-color: #06cc6a;
            transform: translateY(-2px);
        }

        .error-message {
            color: #ff6b6b;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background-color: rgba(255, 107, 107, 0.1);
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="logo">
        VALA
        <span>Creative Internet Solutions</span>
    </div>

    <div class="login-container">
        <h2>Connexion administrateur</h2>

        <?php if (isset($erreur)): ?>
            <div class="error-message"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required />

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required />

            <button type="submit" class="login-button">Se connecter</button>
        </form>
    </div>
</body>
</html>