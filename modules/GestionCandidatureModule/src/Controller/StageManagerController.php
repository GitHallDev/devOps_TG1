<?php
//src/Controller/StageManagerController.php
namespace Modules\GestionCandidatureModule\Controller;

use Modules\GestionCandidatureModule\Service\StageManagerService;
use \DateTime;
class StageManagerController
{

    public static function showStageEffectifPage():string
    {
        return include __DIR__.'/../View/stageEffectifPage.php';
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
        if($stage->create_stage_effectif($data['id_cand'],$data['id_user'],$start_date,$end_date,$data['sujet_effectif'],$data['remuneration_effective'],$data['statuts'],$data['encadreur'])){
            header('Location:Stages');
            exit;
        }
        return 'Un problème est apparue lors du processus de création de stage effectif !';
    }
}