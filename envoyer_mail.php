<?php
/*
 * IMPORTANT :
 * Ce projet utilise un fichier .env pour stocker les identifiants email de l'entreprise VALA.
 * Ne pas partager ce fichier publiquement.
 * 
 * Exemple de contenu du fichier .env :
 * MAIL_HOST=smtp.gmail.com
 * MAIL_PORT=465
 * MAIL_USERNAME=contact@vala.ma
 * MAIL_PASSWORD=mot_de_passe_application
 * MAIL_ENCRYPTION=ssl
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = new PHPMailer(true);

    try {
        // Config SMTP
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION']; // 'ssl'
        $mail->Port       = $_ENV['MAIL_PORT'];       // 465

        // Expéditeur VALA
        $mail->setFrom($_ENV['MAIL_USERNAME'], 'VALA - Creative Internet Solutions');

        // Destinataire
        $destinataire = $_POST['destinataire'];
        $mail->addAddress($destinataire);

        // Joindre le fichier
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0) {
            $mail->addAttachment($_FILES['fichier']['tmp_name'], $_FILES['fichier']['name']);
        } else {
            throw new Exception("Aucun fichier valide sélectionné.");
        }

        // Contenu de l'email
        $mail->isHTML(false);
        $mail->Subject = 'Fichier de VALA';
        $mail->Body    = "Bonjour,\n\nVeuillez trouver ci-joint le fichier envoyé par VALA.\n\nCordialement,\nL'équipe VALA";

        $mail->send();
        $message = "<p style='color:green;'>✅ Email avec fichier envoyé à $destinataire.</p>";
    } catch (Exception $e) {
        $message = "<p style='color:red;'>❌ Erreur : {$mail->ErrorInfo}</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Envoyer un fichier par email</title>
<style>
    body {
        background-color: #ffffff; /* blanc */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        display: flex;
        justify-content: center;  /* centre horizontal */
        align-items: center;      /* centre vertical */
        height: 100vh;
        margin: 0;
    }
    form {
        background-color: #001F3F; /* bleu marine */
        padding: 30px 40px;
        border-radius: 8px;
        width: 400px;
        box-sizing: border-box;
        color: #fff;
        box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #fff;
    }
    input[type="email"],
    input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        box-sizing: border-box;
    }
    input[type="email"]:focus,
    input[type="file"]:focus {
        outline: none;
        box-shadow: 0 0 5px #C9B037; /* doré */
    }
    button {
        width: 100%;
        padding: 12px;
        background-color: #FFC107; /* jaune */
        border: none;
        border-radius: 6px;
        color: #000;
        font-size: 16px;
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #e6ac00;
        color: #000;
    }
    p.message {
        text-align: center;
        margin-bottom: 20px;
        font-weight: 600;
    }
</style>
</head>
<body>

<form method="post" enctype="multipart/form-data">
    <h2 style="color:#fff; text-align:center; margin-bottom: 20px;">Envoyer un fichier par email</h2>

    <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

    <label>Adresse e-mail du client :</label>
    <input type="email" name="destinataire" required />

    <label>Fichier à envoyer :</label>
    <input type="file" name="fichier" accept=".pdf,.doc,.jpg,.png,.zip" required />

    <button type="submit">Envoyer le fichier</button>
</form>

</body>
</html>
