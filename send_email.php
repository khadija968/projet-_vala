<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Paramètres du serveur
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'tjytuyti@gmail.com'; // Ton Gmail
    $mail->Password   = 'ridg whbt pdqh bucw'; // Ton mot de passe d'application
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    // Destinataire
    $mail->setFrom('tjytuyti@gmail.com', 'VALA');
    $mail->addAddress($_POST['email'], $_POST['client']);

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = 'Message concernant votre projet VALA';
    $mail->Body    = nl2br($_POST['message']);
    
    $mail->send();
    echo "✅ Message envoyé avec succès.";
} catch (Exception $e) {
    echo "❌ Échec de l'envoi : " . $mail->ErrorInfo;
}
?>
