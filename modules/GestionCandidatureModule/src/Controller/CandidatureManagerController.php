<?php
// src/Controller/CandidatureManagerController.php
namespace Modules\GestionCandidatureModule\Controller;

use Modules\GestionCandidatureModule\Service\CandidatureManagerService;
use Modules\GestionCandidatureModule\Modele\Candidature;
class CandidatureManagerController
{
    public static function showCandidatureForm():string
    {
        return include __DIR__.'/../View/candidatureForm.php';
    }

    public static function pageCandidatures():string
    {
        return include __DIR__.'/../View/pageCandidatures.php';
    }

    public static function candidater():string{
        $candidature = new CandidatureManagerService(
            new \Modules\GestionCandidatureModule\Repository\CandidatureManagerRepository());

            if($candidature->candidater(new Candidature($_FILES['cv']['tmp_name'],$_FILES['cover_letter']['tmp_name'], $_POST['statuts']),
                                    $_POST['id_user'],$_POST['id_prop'])){
                                        header('Location:candidatures');
                                        exit;
                                    }
                                    return 'Un problème est apparue lors du processus de candidature !';
    }
    public static function getCandidatureCVById():array{
        // recuperer l'id dans l'url
        $id = $_GET['id'];
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false) {
            // Gérer l'erreur, par exemple, rediriger ou afficher un message d'erreur
            return 'ID invalide';
        }
        $id = intval($id);
        $candidature = new CandidatureManagerService(
            new \Modules\GestionCandidatureModule\Repository\CandidatureManagerRepository());
        return $candidature->getCandidatureCVById($id);
    }

        public static function getCandidatureLMById():array{
        // recuperer l'id dans l'url
        $id = $_GET['id'];
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false) {
            // Gérer l'erreur, par exemple, rediriger ou afficher un message d'erreur
            return 'ID invalide';
        }
        $id = intval($id);
        $candidature = new CandidatureManagerService(
            new \Modules\GestionCandidatureModule\Repository\CandidatureManagerRepository());
        return $candidature->getCandidatureLMById($id);
    }

    public static function getAllCandidatures():array{
        $candidature = new CandidatureManagerService(
            new \Modules\GestionCandidatureModule\Repository\CandidatureManagerRepository());
        return $candidature->getAllCandidatures();
    }
}