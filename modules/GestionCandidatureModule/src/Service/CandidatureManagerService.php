<?php
// scr/Service/CandidatureManagerService.php
namespace Modules\GestionCandidatureModule\Service;

use Modules\GestionCandidatureModule\Repository\CandidatureManagerRepository;
use Modules\GestionCandidatureModule\Modele\Candidature;
class CandidatureManagerService 
{
    private CandidatureManagerRepository $repo;

    /**
     * Constructeur de la classe
     */
    public function  __construct(CandidatureManagerRepository $repo){
        $this->repo =$repo;
    }

    /**
     * Soumet une candidature pour un utilisateur donné à une proposition spécifique.
     *
     * @param Candidature $candidature L'objet Candidature contenant les informations du candidat.
     * @param int|string $id_user L'identifiant de l'utilisateur candidatant.
     * @param int|string $id_prop L'identifiant de la proposition à laquelle postuler.
     * @return bool Retourne true si la candidature a été enregistrée avec succès, false sinon.
     */
    public function candidater(Candidature $candidature, $id_user, $id_prop):bool{
        try {
                    $this->repo->saveCandidature(['cv'=>$candidature->getCV(), 'cover_letter'=>$candidature->getCoverLetter(), 'statuts'=>$candidature->getStatuts(), 'id_user'=>$id_user,
        'id_prop'=> $id_prop]);
                
        return true;
        } catch (\Throwable $th) {
            echo "Echec de la candidature:".$th;
            return false;
        }
    }

    public function getAllCandidatures():array{
        return $this->repo->getAllCandidature();
    }

    public function getCandidatureCVById(int $id):array{
        $candidature =  $this->repo->getCandidatureByID($id);
        $candidature['cv'] = $this->repo->getBlobFile($candidature['ID_user'],$id,'cv');
        return $candidature;
    }

     public function getCandidatureLMById(int $id):array{
        $candidature =  $this->repo->getCandidatureByID($id);
        $candidature['cover_letter'] = $this->repo->getBlobFile($candidature['ID_user'],$id,'cover_letter');
        return $candidature;
    }

    public function get_propositionByCandidature($id_prop_in_cand):array{
        return $this->repo->get_propositionBycand($id_prop_in_cand);
    }

}