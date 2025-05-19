<?php
// src/Service/StageManagerService.php
namespace Modules\GestionCandidatureModule\Service;
use Modules\GestionCandidatureModule\Repository\StageManagerRepository;
use \DateTime;

/**
 * Classe de service pour la gestion des stages effectifs.
 */
class StageManagerService
{
    private StageManagerRepository $repo;

    /**
     * Constructeur de la classe
     */
    public function  __construct(StageManagerRepository $repo){
        $this->repo =$repo;
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
    public function create_stage_effectif(?int $id_cand, int $id_user, DateTime $start_date, DateTime $end_date, string $sujet_effectif, int $remuneration_effective, string $statuts, string $encadreur):void{
        try {
             $this->repo->create_stage_effectif($id_cand, $id_user, $start_date, $end_date, $sujet_effectif, $remuneration_effective, $statuts, $encadreur);
        } catch (\Throwable $th) {
            echo "Echec de la création d'un stage effectif:".$th;
        }
    }
}