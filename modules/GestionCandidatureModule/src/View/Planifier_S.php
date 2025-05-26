<?php
session_start();

if(!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
if($_SESSION['user']['role'] !== 'GC') {
    echo '<script> alert(" Vous n\'avez pas accès à cette page 😊 !") </script>';
    header('Location: /Accueil');
    exit;
}

// Récupérer la soutenance si on est en mode édition
$soutenance = null;
if (isset($_GET['id'])) {
    $soutenanceController = new \Modules\GestionCandidatureModule\Controller\SoutenanceController();
    $soutenance = $soutenanceController->getSoutenanceByIdForView($_GET['id'])->toArray();
    // header('Content-Type: text/html');
    // Vérifier si la soutenance peut être modifiée
    $canEdit = false;

    if ($soutenance) {
        $dateNow = new DateTime();
        $dateSoutenance = new DateTime($soutenance['date']);
        $canEdit = $dateSoutenance > $dateNow && $soutenance['resultat'] === 'en_cours';
    }
    
    if (!$canEdit) {
        echo "<script>alert('Cette soutenance ne peut pas être modifiée.');</script>";
        header('Location: /soutenances');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Planifier une Soutenance</title>
    <link rel="stylesheet" href="./styles/GestionCandidature/style_1.css">
</head>
<body>
    <?php include 'header.php'?>
    <?php include 'sidebar.php'?>
    
    <div class="container">
        <h2><?php echo isset($_GET['id']) ? 'Modifier la Soutenance' : 'Planifier une Soutenance' ?></h2>
        
        <?php
        if (isset($_GET['success'])) {
            echo "<p class='success'>Soutenance " . (isset($_GET['id']) ? 'modifiée' : 'planifiée') . " avec succès !</p>";
        } elseif (isset($_GET['error'])) {
            echo "<p class='error'>Erreur lors de l'opération.</p>";
        }
        ?>

        <!-- Modifier le formulaire pour utiliser JavaScript pour l'envoi -->
        <form id="soutenanceForm" onsubmit="submitForm(event)">
            <?php if (isset($_GET['id'])) : ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <div class="form-group">
                <label>Stage :</label>
                <?php
                $stageController = new \Modules\GestionCandidatureModule\Controller\StageManagerController();
                $stages = $stageController->getAllStagesEnCours();
                if (!empty($stages)) {
                    $stage = $stages[0]; // On prend le premier stage
                    echo "<div class='stage-info'>";
                    echo "<strong>Sujet :</strong> " . htmlspecialchars($stage['Sujet_effectif']) . "<br>";
                    echo "<strong>Stagiaire :</strong> " . htmlspecialchars($stage['user_prenom'] . ' ' . $stage['user_nom']);
                    echo "<input type='hidden' name='id_stage' value='{$stage['ID_stage']}'>";
                    echo "</div>";
                } else {
                    echo "<p class='no-stage'>Aucun stage en cours</p>";
                }
                ?>
            </div>

            <div class="form-group">
                <label for="date">Date et heure de la soutenance</label>
                <input type="datetime-local" 
                       id="date" 
                       name="date" 
                       required 
                       min="<?php echo date('Y-m-d\TH:i'); ?>" 
                       value="<?php echo $soutenance ? date('Y-m-d\TH:i', strtotime($soutenance['date'])) : date('Y-m-d\TH:i'); ?>">
            </div>

            <div class="form-group">
                <label>Jury</label>
                <?php for($i = 1; $i <= 3; $i++) : ?>
                    <input type="text" name="jury[]" 
                           placeholder="Membre du jury <?php echo $i ?>" required
                           value="<?php echo $soutenance ? $soutenance['jury'][$i-1] : '' ?>">
                <?php endfor; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <?php echo isset($_GET['id']) ? 'Modifier' : 'Planifier' ?> la soutenance
                </button>
                
                <?php if (isset($_GET['id'])) : ?>
                    <button type="button" class="btn-danger" onclick="deleteSoutenance(<?php echo $_GET['id'] ?>)">
                        Supprimer
                    </button>
                <?php endif; ?>
                
                <a href="/Soutenances" class="btn-secondary">Retour</a>
            </div>
        </form>
    </div>

    <script>
    function deleteSoutenance(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette soutenance ?')) {
            fetch(`/api/soutenances/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/Soutenances';
                } else {
                    alert('Erreur lors de la suppression');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression');
            });
        }
    }
    </script>
    <footer>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </footer>
</body>
</html>

<script>
function submitForm(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = {
        id_stage: formData.get('id_stage'),
        date: formData.get('date'),
        jury: Array.from(formData.getAll('jury[]'))
    };

    console.log(data.jury);

    const id = <?php echo isset($_GET['id']) ? $_GET['id'] : 'null' ?>;
    const method = id ? 'PUT' : 'POST';
    const url = `/api/soutenances${id ? `/${id}` : ''}`;

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.id_stage);
        if (data.success || data.id_stage) {
            window.location.href = '/Soutenances';
        } else {
            alert('Erreur lors de l\'opération');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de l\'opération');
    });
}
</script>
