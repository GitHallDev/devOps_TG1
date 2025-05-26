<?php
//src/Controller/StageManagerController.php
namespace Modules\GestionCandidatureModule\Controller;

use Modules\GestionCandidatureModule\Service\StageManagerService;
use \DateTime;
class StageManagerController
{

    public static function showStageEffectifPage():string
    {
        try {
            $stages = self::getAllStages();
            $stagesEnCours = self::getAllStagesEncours();
            $total = count($stages);
            $encours = count($stagesEnCours);
            $termines = $total - $encours;
            
            return include __DIR__.'/../View/stageEffectifPage.php';
        } catch (\Throwable $th) {
            return "Erreur lors de la récupération des stages : " . $th->getMessage();
        }
    }


    public static function showPlanifierSoutenanceForm():string
    {
        return include __DIR__.'/../View/Planifier_S.php';
    }

    public static function showSoutenancesPage():string
    {
        return include __DIR__.'/../View/soutenancesPage.php';
    }
    
    public static function showREdirectionPage():string
    {
        return include __DIR__.'/../View/Rediriger_S.php';
    }

    /**
     * Crée un stage effectif pour un utilisateur donné.
     *
     * @param int $id_cand L'identifiant de la candidature.
     * @param int $id_user L'identifiant de l'utilisateur.
     * @param DateTime $start_date La date de début du stage.
     * @param DateTime $end_date La date de fin du stage.
     * @param string $sujet_effectif Le sujet effectif du stage.
     * @param int $remuneration_effective La rémunération effective du stage.
     * @param string $statuts Le statut du stage.
     * @param string $encadreur L'encadreur du stage.
     */
    public static function createStageEffectif():string
    {
     try {
           if($_SERVER['HTTP_CONTENT_TYPE']==='application/json'){
            $data = json_decode(file_get_contents('php://input'), true);
        }
        else{
            $data = $_POST;
        }

        $stage = new StageManagerService(
            new \Modules\GestionCandidatureModule\Repository\StageManagerRepository());
            $start_date = new DateTime(); // Date actuelle
            $current_date = new DateTime();
            $end_date = $current_date->modify('+'.$data['duration'].' month'); // Ajoute la durée (mois) à la date actuelle
        if($stage->create_stage_effectif($data['id_cand']??Null,$data['id_user'],$start_date,$end_date,$data['sujet_effectif'],$data['remuneration_effective'],'en_cours',$data['encadreur'])){
            header('Location:Stages');
            exit;
        }
        return 'Un problème est apparue lors du processus de création de stage effectif !';
     } catch (\Throwable $th) {
          echo "Echec de la création d'un stage effectif:".$th;
     }
    }

    public static function getAllStages():array{
        try {
            $stage = new StageManagerService(
                new \Modules\GestionCandidatureModule\Repository\StageManagerRepository());
           return $stage->get_all_stages_effectifs();    
        } catch (\Throwable $th) {
            //throw $th;
            throw new RuntimeException("Erreur innatendue : ". $e->getMessage());    
        }
    }
        
    public static function getAllStagesEncours():array{
        try {
            $stage = new StageManagerService(
                new \Modules\GestionCandidatureModule\Repository\StageManagerRepository());
           return $stage->get_all_stages_effectifs_en_cours();    
        } catch (\Throwable $th) {
            //throw $th;
            throw new RuntimeException("Erreur innatendue : ". $e->getMessage());    
        }
    }

    public static function get_stage_effectif_by_id(int $id): ?array {
        try {
            $stage = new StageManagerService(
                new \Modules\GestionCandidatureModule\Repository\StageManagerRepository());
            $result = $stage->get_stage_effectif_by_id($id);
            
            // Débogage
            error_log("Résultat de la recherche du stage ID $id : " . print_r($result, true));
            
            return $result;
        } catch (\Throwable $th) {
            error_log("Erreur lors de la récupération du stage : " . $th->getMessage());
            return null;
        }
    }

    
    public static function traitementRedirectionStage(): string {
        try {
            if (!isset($_POST['id_stage']) || !isset($_POST['ancien_sujet']) || 
                !isset($_POST['raison']) || !isset($_POST['nouveau_sujet']) || 
                !isset($_POST['nouvel_encadrant'])) {
                throw new \RuntimeException('Tous les champs sont obligatoires');
            }

            $stage = new StageManagerService(
                new \Modules\GestionCandidatureModule\Repository\StageManagerRepository()
            );

            // Mise à jour du stage
            $success = $stage->update_stage_effectif(
                (int)$_POST['id_stage'],
                null, // id_cand n'est pas nécessaire pour la mise à jour
                0, // id_user n'est pas nécessaire pour la mise à jour
                new DateTime(), // date de début inchangée
                new DateTime(), // date de fin inchangée
                $_POST['nouveau_sujet'],
                0, // rémunération inchangée
                'en_cours', // statut inchangé
                $_POST['nouvel_encadrant']
            );

            if ($success) {
                header('Location: /Stages?success=1');
                exit;
            } else {
                header('Location: /Stages?error=1');
                exit;
            }
        } catch (\Throwable $th) {
            error_log("Erreur lors du traitement de la redirection : " . $th->getMessage());
            header('Location: /Stages?error=1');
            exit;
        }
    }

}