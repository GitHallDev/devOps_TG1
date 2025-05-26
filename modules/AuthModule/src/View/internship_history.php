<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship History</title>
    <link rel="stylesheet" href="./styles/variable_style.css">
    <link rel="stylesheet" href="./styles/GestionCompte/Internship_history.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="./ressources/logo.png" alt="Logo" class="profile-logo">
            <span class="profile-title">Internship History</span>
        </div>
        <div class="internship-list">
            <?php if (empty($stages)): ?>
                <div class="internship-item empty">No internship history available.</div>
            <?php else: ?>
                <?php foreach ($stages as $stage): ?>
                    <div class="internship-item">
                        <h3><?php echo htmlspecialchars($stage['Sujet_effectif']); ?></h3>
                        <div class="internship-details">
                            <p><strong>Period:</strong> <?php echo date('d/m/Y', strtotime($stage['Debut_stage'])); ?> - <?php echo date('d/m/Y', strtotime($stage['Fin_stage'])); ?></p>
                            <p><strong>Supervisor:</strong> <?php echo htmlspecialchars($stage['Encadreur'] ?? 'Not specified'); ?></p>
                            <p><strong>Remuneration:</strong> <?php echo number_format($stage['Remuneration_effective'], 0, ',', ' '); ?> FCFA/mois</p>
                            <p><strong>Status:</strong> <?php echo ucfirst(str_replace('_', ' ', $stage['statuts'])); ?></p>
                            
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a href="/profile" class="back-link">&#8592; Back to Profile</a>
    </div>
</body>
</html> 