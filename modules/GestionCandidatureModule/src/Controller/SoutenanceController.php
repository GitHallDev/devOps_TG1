<?php
// src/Controller/SoutenanceController.php
namespace Modules\GestionCandidatureModule\Controller;

use Modules\GestionCandidatureModule\Service\SoutenanceService;
use Modules\GestionCandidatureModule\Repository\SoutenanceRepository;
use \DateTime;
use \Modules\GestionCandidatureModule\Modele\Soutenance;
class SoutenanceController
{

    public static function getAllSoutenances(): string
    {
        $service = new SoutenanceService(new SoutenanceRepository());
        $soutenances = $service->getAllSoutenances();
        echo json_encode($soutenances);
        header('Content-Type: application/json');
        return json_encode($soutenances);
    }

    /**
     * Récupère les détails d'une soutenance par son ID
     * 
     * @return string
     */
    public function getSoutenanceByIdForView(int $id): ?Soutenance
    {
        $service = new SoutenanceService(new SoutenanceRepository());
        return $service->getSoutenanceById($id);
    }

    /**
     * Récupère les détails d'une soutenance par son ID (API)
     * 
     * @return void
     */
    public static function getSoutenanceById(): void
    {
        header('Content-Type: application/json');
        
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de soutenance invalide']);
            return;
        }
        
        $service = new SoutenanceService(new SoutenanceRepository());
        $soutenance = $service->getSoutenanceById($id);
        
        if (!$soutenance) {
            http_response_code(404);
            echo json_encode(['error' => 'Soutenance non trouvée']);
            return;
        }
        
        $soutenanceData = [
            'id' => $soutenance->getId(),
            'date' => $soutenance->getDate(),
            'etudiant' => self::getEtudiantNameFromStage($soutenance->getIdStage()),
            'jury' => $soutenance->getJury(),
            'statut' => $soutenance->getResultat(),
            'ID_stage' => $soutenance->getIdStage(),
        ];
        
        echo json_encode($soutenanceData);
    }

    /**
     * Récupère le nom de l'étudiant à partir de l'ID du stage
     * 
     * @param int $idStage L'ID du stage
     * @return string
     */
    private static function getEtudiantNameFromStage(int $idStage): string
    {
        $c = new \App\Container();
        $pdo = $c->get(\PDO::class);
        
        $stmt = $pdo->prepare('SELECT u.nom, u.prenom 
                              FROM t1_stages s
                              JOIN t1_users u ON s.id_user = u.id_user
                              WHERE s.ID_stage = :id_stage');
        $stmt->execute([':id_stage' => $idStage]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($row) {
            return $row['prenom'] . ' ' . $row['nom'];
        }
        
        return 'Inconnu';
    }

    /**
     * Met à jour le statut d'une soutenance
     * 
     * @return string
     */
    public static function updateSoutenanceStatus(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if (!$id) {
            http_response_code(400);
            return json_encode(['error' => 'ID de soutenance invalide']);
        }
        
        // Récupérer les données JSON du corps de la requête
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['status']) || !in_array($data['status'], ['valide', 'invalide', 'en_cours'])) {
            http_response_code(400);
            return json_encode(['error' => 'Statut invalide']);
        }
        
        $service = new SoutenanceService(new SoutenanceRepository());
        $success = $service->updateSoutenanceStatus(intval($id), $data['status']);
        
        if (!$success) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur lors de la mise à jour du statut']);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);  
        return json_encode(['success' => true]);
    }

    /**
     * Crée une nouvelle soutenance
     * 
     * @return string
     */
    public static function createSoutenance(): string
    {
        if($_SERVER['HTTP_CONTENT_TYPE']==='application/json'){
        $data = json_decode(file_get_contents('php://input'), true);
        }else{
            $data = $_POST;
        }
        if (!self::validateSoutenanceData($data)) {
            http_response_code(400);
            return json_encode(['error' => 'Données invalides']);
        }
        
        $service = new SoutenanceService(new SoutenanceRepository());
        $id = $service->createSoutenance($data);
        
        if (!$id) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur lors de la création de la soutenance']);
        }
        
        http_response_code(201);
        echo json_encode(['id_stage'=>$id]);
        return json_encode(['id' => $id]);
    }

    /**
     * Met à jour une soutenance existante
     * 
     * @return string
     */
    public static function updateSoutenance(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            http_response_code(400);
            return json_encode(['error' => 'ID de soutenance invalide']);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        if (!self::validateSoutenanceData($data)) {
            http_response_code(400);
            return json_encode(['error' => 'Données invalides']);
        }
        
        $service = new SoutenanceService(new SoutenanceRepository());
        $success = $service->updateSoutenance($id, $data);
        
        if (!$success) {
            http_response_code(500);
            return json_encode(['error' => 'Erreur lors de la mise à jour']);
        }
        
        return json_encode(['success' => true]);
    }

    /**
     * Supprime une soutenance
     * 
     * @return string
     */
    public static function deleteSoutenance(): string
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            http_response_code(400);
            return json_encode(['error' => 'ID de soutenance invalide']);
        }
        
        $service = new SoutenanceService(new \Modules\GestionCandidatureModule\Repository\SoutenanceRepository());
        
        // Vérifier si la soutenance peut être supprimée
        $soutenance = $service->getSoutenanceById($id);
        // echo "<pre>";
        // print_r($soutenance->toArray());
        // echo "<\pre>";
        if (!$soutenance) {
            http_response_code(404);
            return json_encode(['error' => 'Soutenance non trouvée']);
        }

        $dateNow = new DateTime();
        $dateSoutenance = new DateTime($soutenance->getDate());

        if ($dateSoutenance <= $dateNow || $soutenance->getResultat() !== 'en_cours') {
            http_response_code(400);
            return json_encode(['error' => 'La soutenance ne peut plus être supprimée']);
        }

        $success = $service->deleteSoutenance($id);
        http_response_code(200);
        echo json_encode(['success' => true]);
        return json_encode(['success' => true]);
    }

    /**
     * Valide les données d'une soutenance
     * 
     * @param array $data
     * @return bool
     */
    private static function validateSoutenanceData(array $data): bool
    {
        return isset($data['id_stage']) &&
               isset($data['date']) &&
               isset($data['jury']);
    }
}