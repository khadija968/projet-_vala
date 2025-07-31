<?php
session_start();

// Vérifier si admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=admins1;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les clients
    $clients = $pdo->query("SELECT * FROM clients ORDER BY nom ASC")->fetchAll();

    // Récupérer tous les projets avec infos clients (jointure)
    $projets = $pdo->query("
        SELECT projets.*, clients.nom AS nom_client, clients.prenom 
        FROM projets 
        JOIN clients ON projets.client_id = clients.id
        ORDER BY projets.date_debut DESC
    ")->fetchAll();

} catch (PDOException $e) {
    die("Erreur base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        body {
            background-color: #ffffff;
            color: #004080;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
        }
        a.button {
            display: inline-block;
            padding: 8px 12px;
            background: #FFC107; /* jaune */
            color: #000;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 10px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }
        a.button:hover {
            background: #e6ac00;
            color: #000;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
            border: 2px solid #004080;
        }
        th, td {
            border: 1px solid #004080;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #004080;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #e6f0ff;
        }
        tr:hover {
            background-color: #cce0ff;
        }
        h2, h3 {
            margin-top: 0;
            color: #004080;
        }
        .btn-action {
            padding: 6px 10px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            background-color: #FFC107;
            color: black;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-action:hover {
            background-color: #e6ac00;
        }
    </style>
</head>
<body>

<h2>Tableau de bord administrateur</h2>

<!-- Boutons d'ajout -->
<a href="ajouter_client.php" class="button">➕ Ajouter un client</a>
<a href="ajouter_projet.php" class="button">➕ Ajouter un projet</a>
<a href="statistiques.php" class="button">📊 Statistiques</a>


<h3>Clients</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Email</th>
        <th>Username</th>
    </tr>
    <?php foreach ($clients as $client): ?>
    <tr>
        <td><?= $client['id'] ?></td>
        <td><?= htmlspecialchars($client['nom']) ?></td>
        <td><?= htmlspecialchars($client['prenom']) ?></td>
        <td><?= htmlspecialchars($client['email']) ?></td>
        <td><?= htmlspecialchars($client['username']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h3>Projets</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Client</th>
        <th>Titre</th>
        <th>Statut</th>
        <th>Date début</th>
        <th>Date fin</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($projets as $projet): ?>
    <tr>
        <td><?= $projet['id'] ?></td>
        <td><?= htmlspecialchars($projet['nom_client']) . ' ' . htmlspecialchars($projet['prenom']) ?></td>
        <td><?= htmlspecialchars($projet['titre']) ?></td>
        <td><?= htmlspecialchars($projet['statut']) ?></td>
        <td><?= htmlspecialchars($projet['date_debut']) ?></td>
        <td><?= htmlspecialchars($projet['date_fin']) ?></td>
        <td>
            <a href="message.php?projet_id=<?= $projet['id'] ?>">
                <button class="btn-action">💬 Messagerie</button>
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
