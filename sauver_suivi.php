<?php
require 'db.php';
$stmt = $pdo->prepare("INSERT INTO suivis (stagiaire_id, travail, progression, commentaire)
                       VALUES (?, ?, ?, ?)");
$stmt->execute([
  $_POST["id"],
  $_POST["travail"],
  $_POST["progression"],
  $_POST["commentaire"]
]);

echo "✅ Suivi enregistré avec succès.";
?>
