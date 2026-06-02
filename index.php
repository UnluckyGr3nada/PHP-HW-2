<?php
$pdo = new PDO('mysql:host=localhost;dbname=Eval_PHP2;charset=utf8', 'root', '');

//  QUery pour recupérer les données 
$mainQuery = $pdo->query("
    SELECT s.id, s.name, COUNT(st.id) as total_students, COUNT(DISTINCT ss.student_id) as active_students
    FROM schools s
    LEFT JOIN students st ON s.id = st.school_id
    LEFT JOIN student_sports ss ON st.id = ss.student_id
    GROUP BY s.id, s.name
");
$schools = $mainQuery->fetchAll(PDO::FETCH_ASSOC);


// sport breakdown par école pour le classement croissant
$activityQuery = $pdo->query("
    SELECT st.school_id, COUNT(ss.sport_id) as total_activities
    FROM students st
    JOIN student_sports ss ON st.id = ss.student_id
    GROUP BY st.school_id
");

// Transforme le résultat en un tableau associatif pour un accès rapide par school_id
$activitiesMap = $activityQuery->fetchAll(PDO::FETCH_KEY_PAIR);

//  Prépare la requête pour obtenir le classement des sports par école (ordre croissant)
$sportsStmt = $pdo->prepare("
    SELECT sp.name, COUNT(ss.student_id) as student_count
    FROM student_sports ss
    JOIN sports sp ON ss.sport_id = sp.id
    JOIN students st ON ss.student_id = st.id
    WHERE st.school_id = :school_id
    GROUP BY sp.id, sp.name
    ORDER BY student_count ASC
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques Écoles</title>
    <style>
        body { font-family: sans-serif; margin: 30px; background: #f4f6f9; }
        .card { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn { display: inline-block; background: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-bottom: 20px; }
        ul { margin: 5px 0 0 0; padding-left: 20px; }
    </style>
</head>
<body>

    <h1>Tableau de Bord des Écoles</h1>
    
    <a href="generate.php" class="btn">🔄 Régénérer les données aléatoires</a>

    <?php foreach ($schools as $school): 
        
        $totalActivities = $activitiesMap[$school['id']] ?? 0;
        
        // Fetch le classement des sports pour cette école
        $sportsStmt->execute([':school_id' => $school['id']]);
        $sportsBreakdown = $sportsStmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <div class="card">
            <h2><?= htmlspecialchars($school['name']) ?></h2>
            <p><strong>Nombre d'élèves total :</strong> <?= $school['total_students'] ?></p>
            <p><strong>Élèves pratiquant au moins un sport :</strong> <?= $school['active_students'] ?></p>
            <p><strong>Nombre total d'activités pratiquées :</strong> <?= $totalActivities ?></p>
            
            <p><strong>Classement des activités (croissant) :</strong></p>
            <?php if (empty($sportsBreakdown)): ?>
                <p>Aucun sport pratiqué dans cette école.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($sportsBreakdown as $sport): ?>
                        <li><?= htmlspecialchars($sport['name']) ?> : <?= $sport['student_count'] ?> élève(s)</li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

</body>
</html>