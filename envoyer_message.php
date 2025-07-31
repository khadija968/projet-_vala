<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=admins1;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer l'ID du projet
$projet_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$message_envoye = "";
$erreur = "";

// Récupérer les infos du projet
$stmt = $pdo->prepare("SELECT * FROM projets WHERE id = ?");
$stmt->execute([$projet_id]);
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$projet) {
    die("Projet introuvable.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    $to_email = $projet['email'];
    $sujet = "Message concernant votre projet : " . $projet['titre'];

    if (!empty($message)) {
        $mail = new PHPMailer(true);
        try {
            // Configuration SMTP Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tjytuyti@gmail.com';
            $mail->Password = 'ridgwhbtpdqhbucw'; // Sans espace !!
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('tjytuyti@gmail.com', 'VALA');
            $mail->addAddress($to_email, $projet['client']);

            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body = "<p>$message</p><p>— L’équipe VALA</p>";

            $mail->send();
            $message_envoye = "✅ Message envoyé avec succès à " . htmlspecialchars($to_email);
        } catch (Exception $e) {
            $erreur = "❌ Échec de l'envoi : " . $mail->ErrorInfo;
        }
    } else {
        $erreur = "❗ Le message est vide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Discussion projet #<?= $projet['id'] ?> - <?= htmlspecialchars($projet['titre']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f4f4f4; }
        .msg { margin: 10px 0; padding: 10px; border-radius: 8px; background: #fff; box-shadow: 0 0 4px #ccc; }
        .success { color: green; }
        .error { color: red; }
        form { margin-top: 20px; }
        textarea { width: 100%; height: 100px; }
        input[type="submit"] { padding: 10px 20px; background: #007BFF; color: #fff; border: none; cursor: pointer; }
    </style>
</head>
<body>

<h2>💬 Discussion projet #<?= $projet['id'] ?> - <?= htmlspecialchars($projet['titre']) ?></h2>

<?php if ($message_envoye): ?>
    <div class="msg success"><?= $message_envoye ?></div>
<?php endif; ?>

<?php if ($erreur): ?>
    <div class="msg error"><?= $erreur ?></div>
<?php endif; ?>

<form method="post">
    <label for="message">Écrire votre message ici :</label><br>
    <textarea name="message" required></textarea><br>
    <input type="submit" value="📨 Envoyer le message">
</form>

</body>
</html>
