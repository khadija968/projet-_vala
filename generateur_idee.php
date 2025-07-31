<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur d'idées de stage</title>
</head>
<body>
    <h2>🎓 Générer une idée de stage personnalisée</h2>

    <form method="post" action="idee_stage_ia.php">
        <label for="stagiaire">Choisir un stagiaire :</label>
        <select name="stagiaire_id" required>
            <?php
            $stmt = $pdo->query("SELECT * FROM stagiaires");
            while ($stagiaire = $stmt->fetch()) {
                echo '<option value="' . $stagiaire['id'] . '">' . htmlspecialchars($stagiaire['nom']) . ' - ' . htmlspecialchars($stagiaire['domaine']) . '</option>';
            }
            ?>
        </select>
        <br><br>
        <button type="submit">💡 Générer l’idée</button>
    </form>
</body>
</html>
