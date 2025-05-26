<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Candidatures</title>
    <link rel="stylesheet" href="./styles/GestionCompte/candidacy_style.css">
    <style>
        .eye-open {
            width: 20px;
            height: 20px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="/profile" class="back-button">← Retour au profil</a>
            <h1>Mes Candidatures</h1>
        </div>
        
        <?php if (empty($candidatures)): ?>
            <p class="no-candidatures">Vous n'avez pas encore de candidatures.</p>
        <?php else: ?>
            <div class="candidatures-list">
                <?php foreach ($candidatures as $candidature): ?>
                    <div class="candidature-card">
                        <h2><?php echo htmlspecialchars($candidature['sujet']); ?></h2>
                        
                        <div class="candidature-details">
                            <p><strong>Durée:</strong> <?php echo htmlspecialchars($candidature['Duree']); ?> mois</p>
                            <p><strong>Rémunération:</strong> <?php echo htmlspecialchars($candidature['remuneration']); ?> FCFA</p>
                        </div>

                        <div class="candidature-status">
                            <p><strong>Statut de la candidature:</strong> 
                                <span class="status-<?php echo $candidature['statuts']; ?>">
                                    <?php 
                                        switch($candidature['statuts']) {
                                            case 'en_cours':
                                                echo 'En cours';
                                                break;
                                            case 'rejeter':
                                                echo 'Rejetée';
                                                break;
                                            case 'accepter':
                                                echo 'Acceptée';
                                                break;
                                            default:
                                                echo 'En attente';
                                        }
                                    ?>
                                </span>
                            </p>
                            <p><strong>Statut de la proposition:</strong>
                                <span class="status-<?php echo $candidature['proposition_statut']; ?>">
                                    <?php 
                                        switch($candidature['proposition_statut']) {
                                            case 'en_cours':
                                                echo 'En cours';
                                                break;
                                            case 'rejeter':
                                                echo 'Rejetée';
                                                break;
                                            case 'accepter':
                                                echo 'Acceptée';
                                                break;
                                            default:
                                                echo 'En attente';
                                        }
                                    ?>
                                </span>
                            </p>
                        </div>

                        <div class="candidature-documents">
                            <p><strong>CV:  </strong><a href="<?php echo"./candidatureCV?id=".$candidature['ID_cand'];?>">
                                        Voir CV
                        </a></p>
                            <p><strong>Lettre de motivation:  </strong> <a href="<?php echo"./candidatureLM?id=".$candidature['ID_cand'];?>">
                                        Voir CV
                        </a></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>