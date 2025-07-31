<?php
// Importation des classes PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

session_start();

// Connexion à la base de données 'admin2'
$conn = new mysqli("localhost", "root", "", "admin2");
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Vérifier si la colonne 'contact_email' existe, sinon la créer
$result = $conn->query("SHOW COLUMNS FROM projets LIKE 'contact_email'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE projets ADD COLUMN contact_email VARCHAR(255) AFTER titre");
}

// Gestion des messages de notification
$message = '';
if (isset($_GET['archive'])) {
    if ($_GET['archive'] === 'success') {
        $message = '<div class="notification success">✅ Projet archivé avec succès</div>';
    } elseif (isset($_GET['erreur'])) {
        $message = '<div class="notification error">❌ Erreur: ' . htmlspecialchars($_GET['erreur']) . '</div>';
    }
}

// Récupération de l'ID du projet sélectionné
$projet_id = isset($_GET['projet_id']) ? (int)$_GET['projet_id'] : 0;
$contact_email = '';
$projet_titre = '';

if ($projet_id > 0) {
    $sql = "SELECT titre, contact_email FROM projets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projet_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $projet = $result->fetch_assoc();
        $contact_email = $projet['contact_email'];
        $projet_titre = $projet['titre'];
    }
    $stmt->close();
}

// Envoi de mail si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contenu']) && !empty($contact_email)) {
    $contenu = $_POST['contenu'];

    $mail = new PHPMailer(true);
    try {
        // Configuration SMTP pour Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tjytuyti@gmail.com';
        $mail->Password   = 'ridg whbt pdqh bucw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Expéditeur et destinataire
        $mail->setFrom('tjytuyti@gmail.com', 'VALA - Internet Solutions');
        $mail->addAddress($contact_email);

        // Contenu du mail
        $mail->isHTML(true);
        $mail->Subject = "Message concernant le projet : $projet_titre";
        $mail->Body    = nl2br(htmlspecialchars($contenu));

        // Envoi
        $mail->send();

        // Enregistrement du message dans la table 'messages'
        $stmt = $conn->prepare("INSERT INTO messages (projet_id, sender, contenu) VALUES (?, 'admin', ?)");
        $stmt->bind_param("is", $projet_id, $contenu);
        $stmt->execute();
        $stmt->close();

        $notification = "<div class='notification success'>📧 Message envoyé avec succès à $contact_email</div>";
    } catch (Exception $e) {
        $notification = "<div class='notification error'>❌ Erreur d'envoi : " . htmlspecialchars($mail->ErrorInfo) . "</div>";
    }
}

// Récupérer la liste des projets non archivés
$projets = $conn->query("SELECT id, titre, contact_email, description, statut FROM projets WHERE statut != 'archivé'");

// Récupérer les projets avec notifications
$messagesProjet = [];
$resNotif = $conn->query("SELECT DISTINCT cible_id FROM support_messages WHERE cible_type = 'projet'");
while ($row = $resNotif->fetch_assoc()) {
    $messagesProjet[] = $row['cible_id'];
}

// Récupérer les messages liés au projet sélectionné
if ($projet_id > 0) {
    $res = $conn->query("SELECT * FROM messages WHERE projet_id = $projet_id ORDER BY date_envoi ASC");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Projets - VALA</title>
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
    .add-button {
      background-color: var(--accent);
      color: var(--primary);
      border: none;
      padding: 12px 24px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 20px;
      margin-left: 10px;
    }
    .add-button:hover {
      background-color: #06cc6a;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(8, 255, 136, 0.3);
    }
    .button-group {
      text-align: right;
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
      color: var(--secondary);
    }
    .table-section h1 {
      font-size: 32px;
      margin-bottom: 30px;
      color: var(--accent);
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
    a.action-link {
      margin-right: 12px;
      text-decoration: none;
      color: var(--accent);
      font-weight: 500;
    }
    a.action-link:hover {
      color: #06cc6a;
    }
    .chat-container {
      background-color: var(--dark-bg);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      margin-top: 20px;
      color: var(--secondary);
    }
    .chat-box {
      background: #121212;
      padding: 20px;
      border-radius: 6px;
      max-height: 300px;
      overflow-y: auto;
      margin-bottom: 20px;
    }
    .message {
      padding: 12px;
      margin-bottom: 10px;
      background: rgba(255,255,255,0.05);
      border-radius: 4px;
    }
    .message-time {
      float: right;
      font-size: 0.8em;
      color: #aaa;
    }
    .admin {
      color: var(--accent);
      font-weight: bold;
    }
    .client {
      color: #4DABF7;
      font-weight: bold;
    }
    .chat-form textarea {
      width: 100%;
      padding: 12px;
      border-radius: 6px;
      background: var(--form-bg);
      color: var(--secondary);
      margin-bottom: 15px;
      border: 1px solid #444;
      font-family: 'Inter', sans-serif;
    }
    .chat-form button {
      background: var(--accent);
      padding: 12px 24px;
      border: none;
      color: var(--primary);
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      width: 100%;
      transition: all 0.3s ease;
    }
    .chat-form button:hover {
      background: #06cc6a;
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
    <?php echo $message; ?>
    
    <div class="button-group">
      <a href="ajouter_projet.php" class="add-button">+ Ajouter un projet</a>
      <a href="statistiques.php" class="add-button">📊 Statistiques</a>
    </div>
    
    <div class="table-section">
      <h1>📁 Liste des projets</h1>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Email</th>
            <th>Description</th>
            <th>Statut</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($projets && $projets->num_rows > 0): ?>
            <?php while($projet = $projets->fetch_assoc()): ?>
              <tr>
                <td data-label="ID"><?= $projet['id'] ?></td>
                <td data-label="Titre"><?= htmlspecialchars($projet['titre']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($projet['contact_email']) ?></td>
                <td data-label="Description"><?= htmlspecialchars(substr($projet['description'], 0, 50)) . '...' ?></td>
                <td data-label="Statut"><?= htmlspecialchars($projet['statut']) ?></td>
                <td data-label="Actions">
                  <a href="?projet_id=<?= $projet['id'] ?>" class="action-link">💬</a>
                  <?php if (in_array($projet['id'], $messagesProjet)): ?>
                    <a href="historique_support.php?type=projet&id=<?= $projet['id'] ?>" class="action-link">🔔</a>
                  <?php endif; ?>
                  <a href="archiver_projet.php?id=<?= $projet['id'] ?>" class="action-link" style="color: red;">Archiver</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" style="text-align: center;">Aucun projet trouvé</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($projet_id > 0): ?>
      <div class="chat-container">
        <h2>💬 Discussion projet #<?= $projet_id ?> - <?= htmlspecialchars($projet_titre) ?></h2>

        <?php if (isset($notification)) echo $notification; ?>

        <div class="chat-box">
          <?php if (isset($res) && $res && $res->num_rows > 0): ?>
            <?php while ($msg = $res->fetch_assoc()): ?>
              <div class="message">
                <span class="<?= htmlspecialchars($msg['sender']) ?>"><?= ucfirst(htmlspecialchars($msg['sender'])) ?> :</span>
                <?= nl2br(htmlspecialchars($msg['contenu'])) ?>
                <span class="message-time"><?= htmlspecialchars($msg['date_envoi']) ?></span>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p>Aucun message pour ce projet.</p>
          <?php endif; ?>
        </div>

        <form class="chat-form" method="POST">
          <textarea name="contenu" placeholder="Écrire votre message ici..." required></textarea>
          <button type="submit">Envoyer le message</button>
        </form>
      </div>
    <?php endif; ?>
  </main>

  <footer>
    <p>© <?= date('Y') ?> VALA - Creative Internet Solutions. Tous droits réservés.</p>
  </footer>

</body>
</html>