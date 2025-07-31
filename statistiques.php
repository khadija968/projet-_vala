<?php
session_start();

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=admin2;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération des statistiques (version optimisée avec exclusion des projets archivés)
$stmt = $pdo->prepare("
    SELECT 
        SUM(CASE WHEN statut != 'archivé' THEN 1 ELSE 0 END) as total,
        SUM(CASE WHEN statut = 'en cours' AND statut != 'archivé' THEN 1 ELSE 0 END) as en_cours,
        SUM(CASE WHEN statut = 'terminé' AND statut != 'archivé' THEN 1 ELSE 0 END) as termines,
        SUM(CASE WHEN statut = 'en attente' AND statut != 'archivé' THEN 1 ELSE 0 END) as en_attente
    FROM projets
");
$stmt->execute();
$data = $stmt->fetch(PDO::FETCH_ASSOC);

// Calcul du taux d'avancement
$taux = ($data['total'] > 0) ? round(($data['termines'] / $data['total']) * 100) : 0;
$data['taux'] = $taux;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques Agence - VALA</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            --card-bg: #FFFFFF;
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

        .container {
            padding: 40px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        h2 {
            color: var(--accent);
            font-size: 32px;
            margin-bottom: 30px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            color: var(--text);
            border: 1px solid #e0e0e0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .card strong {
            color: var(--primary);
            font-size: 1.5em;
            display: block;
            margin-top: 10px;
        }

        .chart-container {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #e0e0e0;
        }

        #chart {
            max-width: 100%;
            margin: 0 auto;
        }

        footer {
            background-color: var(--dark-bg);
            color: var(--secondary);
            padding: 30px 5%;
            text-align: center;
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            nav a {
                margin-left: 15px;
                font-size: 14px;
            }
            
            .chart-container {
                padding: 15px;
            }
        }

        /* Ajout d'animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stats-grid .card {
            animation: fadeIn 0.5s ease forwards;
        }

        .stats-grid .card:nth-child(1) { animation-delay: 0.1s; }
        .stats-grid .card:nth-child(2) { animation-delay: 0.2s; }
        .stats-grid .card:nth-child(3) { animation-delay: 0.3s; }
        .stats-grid .card:nth-child(4) { animation-delay: 0.4s; }
        .stats-grid .card:nth-child(5) { animation-delay: 0.5s; }
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
        <h2>Statistiques de l'agence</h2>
        
        <div class="stats-grid">
            <div class="card">
                <span>Total projets actifs</span>
                <strong><?= $data['total'] ?></strong>
            </div>
            <div class="card">
                <span>✅ Terminés</span>
                <strong><?= $data['termines'] ?></strong>
            </div>
            <div class="card">
                <span>🔄 En cours</span>
                <strong><?= $data['en_cours'] ?></strong>
            </div>
            <div class="card">
                <span>⏳ En attente</span>
                <strong><?= $data['en_attente'] ?></strong>
            </div>
            <div class="card">
                <span>📈 Taux d'avancement</span>
                <strong><?= $data['taux'] ?>%</strong>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="chart"></canvas>
        </div>
    </div>

    <footer>
        <p>© <?= date('Y') ?> VALA - Creative Internet Solutions. Tous droits réservés.</p>
    </footer>

    <script>
    const data = {
        en_cours: <?= $data['en_cours'] ?>,
        termines: <?= $data['termines'] ?>,
        en_attente: <?= $data['en_attente'] ?>
    };

    const ctx = document.getElementById('chart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['En cours', 'Terminés', 'En attente'],
            datasets: [{
                label: 'Répartition des projets',
                data: [data.en_cours, data.termines, data.en_attente],
                backgroundColor: [
                    'rgba(241, 196, 15, 0.8)',
                    'rgba(46, 204, 113, 0.8)',
                    'rgba(230, 126, 34, 0.8)'
                ],
                borderColor: [
                    'rgba(241, 196, 15, 1)',
                    'rgba(46, 204, 113, 1)',
                    'rgba(230, 126, 34, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: {
                        color: '#333',
                        font: {
                            size: 14
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Répartition des projets par statut',
                    color: '#333',
                    font: {
                        size: 16,
                        weight: 'bold'
                    },
                    padding: {
                        top: 10,
                        bottom: 20
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
    </script>
</body>
</html>