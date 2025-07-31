<?php
// Connexion à la base admin2
$conn = new mysqli("localhost", "root", "", "admin2");
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Récupérer l'ID du projet depuis l'URL
$projet_id = isset($_GET['projet_id']) ? (int)$_GET['projet_id'] : 0;

// Traitement envoi message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['contenu'])) {
    $sender = $_POST['sender']; // 'admin' ou 'client'
    $contenu = $_POST['contenu'];

    $stmt = $conn->prepare("INSERT INTO messages (projet_id, sender, contenu) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $projet_id, $sender, $contenu);
    $stmt->execute();
}

// Récupération des messages
$sql = "SELECT * FROM messages WHERE projet_id = $projet_id ORDER BY date_envoi ASC";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie Projet</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .chat-box { background: white; border: 1px solid #ccc; padding: 10px; max-height: 300px; overflow-y: auto; }
        .message { margin-bottom: 10px; }
        .admin { color: blue; font-weight: bold; }
        .client { color: green; font-weight: bold; }
    </style>
</head>
<body>

<h2>💬 Discussion du projet #<?= $projet_id ?></h2>

<div class="chat-box">
    <?php while ($msg = $res->fetch_assoc()): ?>
        <div class="message">
            <span class="<?= $msg['sender'] ?>"><?= ucfirst($msg['sender']) ?> :</span>
            <?= nl2br(htmlspecialchars($msg['contenu'])) ?>
            <small style="float:right; color:gray"><?= $msg['date_envoi'] ?></small>
        </div>
    <?php endwhile; ?>
</div>

<h3>Envoyer un message</h3>
<form method="POST">
    <input type="hidden" name="sender" value="admin"><!-- ou client si besoin -->
    <textarea name="contenu" rows="4" cols="60" placeholder="Écrire votre message ici..." required></textarea><br>
    <button type="submit">Envoyer</button>
</form>

</body>
</html>
