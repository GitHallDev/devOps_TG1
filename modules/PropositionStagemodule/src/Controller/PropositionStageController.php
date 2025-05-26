<?php
// src/modules/PropositionStagemodule/Controller/PropositionStageController.php
namespace Modules\PropositionStagemodule\Controller;

use App\Controller;
use App\Container;
use Modules\PropositionStagemodule\Service\PropositionStageService; 
use Modules\PropositionStagemodule\Repository\PropositionStageRepository;

    
class PropositionStageController 
{
    private static function getService(): PropositionStageService
    {
        $container = new Container();
        $pdo = $container->get(\PDO::class);
        $repository = new \Modules\PropositionStagemodule\Repository\PropositionStageRepository($pdo);
        return new PropositionStageService($repository);
    }

    public static function index():string
    {   
        return include __DIR__ .'/../View/index.php';
    }

    public static function proposals():string
    {   
        return include __DIR__ .'/../View/proposals.php';
    }

    public static function getAllPropositions():array
    {
        return self::getService()->getAllProposition();
    }

    public static function getAllPropositionsAccepted():array
    {
        return self::getService()->getAllPropositionAccepted();
    }

    public static function getLatestPropositions(int $limit = 10):array
    {
        return self::getService()->getLatestPropositions($limit);
    }

    // integration Salamata partie

    public static function showCreateProposition(): string
    {   
        try {
            // // Génération du token CSRF
            // if (!isset($_SESSION['csrf_token'])) {
            //     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // }
            
            // ob_start();
            // require __DIR__.'/../View/CreateProposition.php';
            return include __DIR__.'/../View/CreateProposition.php';

            // $content = ob_get_clean();
            
            // if (empty($content)) {
            //     throw new \RuntimeException('La vue CreateProposition.php n\'a pas généré de contenu');
            // }
            
            // return $content;
        } catch (\Throwable $e) {
            error_log('Erreur dans showCreateProposition: ' . $e->getMessage());
            throw new \RuntimeException('Erreur lors du chargement du formulaire de création : ' . $e->getMessage());
        }
    }


        public static function PropositionBoard(): string
    {
        try {
            return include  __DIR__.'/../View/PropositionBoard.php';
        } catch (\Throwable $e) {
            error_log('Erreur dans PropositionBoard: ' . $e->getMessage());
            throw new \RuntimeException('Erreur lors du chargement du tableau des propositions : ' . $e->getMessage());
        }
    }


        public static function showEditionPropositionForm(): string
    {
        try {
            // $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            // if (!$id) {
            //     $_SESSION['error_message'] = 'ID de proposition invalide';
            //     header('Location: /PropositionBoard');
            //     exit;
            // }

            // $service = self::getService();
            // $proposition = $service->getPropositionById($id);
            
            // if (!$proposition) {
            //     $_SESSION['error_message'] = 'Proposition non trouvée';
            //     header('Location: /proposition/board');
            //     exit;
            // }

            // // Génération du token CSRF
            // if (!isset($_SESSION['csrf_token'])) {
            //     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // }

            // ob_start();
            // require __DIR__.'/../View/EditProposition.php';
            return include __DIR__.'/../View/EditProposition.php';
            // $content = ob_get_clean();
            
            // if (empty($content)) {
            //     throw new \RuntimeException('La vue EditProposition.php n\'a pas généré de contenu');
            // }
            
            // return $content;
        } catch (\Throwable $e) {
            error_log('Erreur dans showEditionPropositionForm: ' . $e->getMessage());
            throw new \RuntimeException('Erreur lors du chargement du formulaire d\'édition : ' . $e->getMessage());
        }
    }

        public static function showChangeStatusProposition(): string
    {
        try {
            // $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            // if (!$id) {
            //     $_SESSION['error_message'] = 'ID de proposition invalide';
            //     header('Location: /proposition/board');
            //     exit;
            // }

            // $service = self::getService();
            // $proposition = $service->getPropositionById($id);
            
            // if (!$proposition) {
            //     $_SESSION['error_message'] = 'Proposition non trouvée';
            //     header('Location: /proposition/board');
            //     exit;
            // }

            // // Génération du token CSRF
            // if (!isset($_SESSION['csrf_token'])) {
            //     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // }

            // ob_start();
            // require __DIR__.'/../View/ChangeStatusProposition.php';
            return include __DIR__.'/../View/ChangeStatusProposition.php';
            // $content = ob_get_clean();
            
            // if (empty($content)) {
            //     throw new \RuntimeException('La vue ChangeStatusProposition.php n\'a pas généré de contenu');
            // }
            
            // return $content;
        } catch (\Throwable $e) {
            error_log('Erreur dans showChangeStatusProposition: ' . $e->getMessage());
            throw new \RuntimeException('Erreur lors du chargement du formulaire de changement de statut : ' . $e->getMessage());
        }
    }


        public static function showDeleteProposition(): string
    {
        try {
            // $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            // if (!$id) {
            //     $_SESSION['error_message'] = 'ID de proposition invalide';
            //     header('Location: /proposition/board');
            //     exit;
            // }

            // $service = self::getService();
            // $proposition = $service->getPropositionById($id);
            
            // if (!$proposition) {
            //     $_SESSION['error_message'] = 'Proposition non trouvée';
            //     header('Location: /proposition/board');
            //     exit;
            // }

            // // Génération du token CSRF
            // if (!isset($_SESSION['csrf_token'])) {
            //     $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // }

            // ob_start();
            // require __DIR__.'/../View/DeleteProposition.php';
            return include __DIR__.'/../View/DeleteProposition.php';
            // $content = ob_get_clean();
            
            // if (empty($content)) {
            //     throw new \RuntimeException('La vue DeleteProposition.php n\'a pas généré de contenu');
            // }
            
            // return $content;
        } catch (\Throwable $e) {
            error_log('Erreur dans showDeleteProposition: ' . $e->getMessage());
            throw new \RuntimeException('Erreur lors du chargement du formulaire de suppression : ' . $e->getMessage());
        }
    }

     public static function saveProposition(): void
    {
        try {
            if($_SERVER['HTTP_CONTENT_TYPE']==='application/json'){
            $data = json_decode(file_get_contents('php://input'), true);
        }
        else{
            $data = $_POST;
        }

            $sujet = $data['sujet'];
            $duree = $data['Duree'];
            $remuneration = $data['remuneration'];
            $createBy =$data['createBy'];

            if (empty($sujet) || empty($duree) || empty($remuneration) || empty($createBy)) {
                throw new \RuntimeException('Tous les champs sont obligatoires');
            }

            $service = self::getService();
            $proposition = new \Modules\PropositionStagemodule\Modele\Proposition(
                $sujet,
                $duree,
                $remuneration,
                'en_cours',
                $createBy
            );

            if (!$service->enregistrerProposition($proposition)) {
                throw new \RuntimeException('Erreur lors de l\'ajout de la proposition');
            }
            header('Location: /PropositionBoard');
            exit;
        } catch (\Throwable $e) {
            error_log('Erreur dans saveProposition: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Erreur lors de l\'ajout de la proposition : ' . $e->getMessage();
            header('Location: /proposition/create');
            exit;
        }
    }

    public static function updateProposition(): void
    {
        try {

            if($_SERVER['HTTP_CONTENT_TYPE']==='application/json'){
                $data = json_decode(file_get_contents('php://input'), true);
             }
            else{
                $data = $_POST;
            }

            $id = $data['id'];
            if (!$id) {
                throw new \RuntimeException('ID invalide pour la modification');
            }

            $proposition = [
                'sujet' => $data['sujet'],
                'Duree' => $data['Duree'],
                'remuneration' => $data['remuneration'],
                'statuts' => $data['statuts'] ?? 'en_cours',
                'create_by' => $data['create_by']
            ];

            $service = self::getService();
            $service->updateProposition($id, $proposition);
            
            header('Location: /PropositionBoard');
            exit;
        } catch (\Throwable $e) {
            error_log('Erreur dans updateProposition: ' . $e->getMessage());
            throw new \RuntimeException('Erreur lors de la modification de la proposition : ' . $e->getMessage());
        }
    }

    public static function updateStatutProposition(): void
    {
        try {
            // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            //     throw new \RuntimeException('Erreur de sécurité : Token CSRF invalide');
            // }
            if($_SERVER['HTTP_CONTENT_TYPE']==='application/json'){
                $data = json_decode(file_get_contents('php://input'), true);
             }
            else{
                $data = $_POST;
            }
            $id = $data['id'];
            $nouveauStatut = $data['statuts'];
            echo "id= ".$id." nouveau statut= ".$nouveauStatut;
            if ($id === false || empty($nouveauStatut)) {
                throw new \RuntimeException('Données invalides pour la mise à jour du statut');
            }

            $statutsValides = ['en_cours', 'accepter', 'rejeter'];
            if (!in_array($nouveauStatut, $statutsValides)) {
                throw new \RuntimeException('Statut invalide');
            }

            $service = self::getService();
            if (!$service->changerStatut($id, $nouveauStatut)) {
                throw new \RuntimeException('Erreur lors de la modification du statut');
            }

            $_SESSION['flash_message'] = 'Le statut a été modifié avec succès';
            header('Location: /PropositionBoard');
            exit;
        } catch (\Throwable $e) {
            error_log('Erreur dans updateStatutProposition: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Erreur lors de la modification du statut : ' . $e->getMessage();
            header('Location: /PropositionBoard');
            exit;
        }
    }


    public static function deleteProposition(): void
    {
        try {
            // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            //     throw new \RuntimeException('Erreur de sécurité : Token CSRF invalide');
            // }
            if($_SERVER['HTTP_CONTENT_TYPE']==='application/json'){
                $data = json_decode(file_get_contents('php://input'), true);
                $id = $data['id'];
            }else{
                $id = $_POST['id'];
            }
            echo "id= ".$id;
            if ($id === false) {
                throw new \RuntimeException('ID invalide pour la suppression');
            }

            $service = self::getService();
            $proposition = $service->getPropositionById($id);
            
            if (!$proposition) {
                throw new \RuntimeException('La proposition n\'existe pas');
            }

            if (!$service->deleteProposition($id)) {
                throw new \RuntimeException('Erreur lors de la suppression de la proposition');
            }

            // $_SESSION['flash_message'] = 'La proposition a été supprimée avec succès';
            header('Location: /PropositionBoard');
            exit;
        } catch (\Throwable $e) {
            error_log('Erreur dans deleteProposition: ' . $e->getMessage());
            $_SESSION['error_message'] = 'Erreur lors de la suppression : ' . $e->getMessage();
            header('Location: /PropositionBoard');
            exit;
        }
    }

    public static function getPropositionById(int $id): ?array
    {
        try {
            $service = self::getService();
            return $service->getPropositionById($id);
        } catch (\Throwable $e) {
            error_log('Erreur dans getPropositionById: ' . $e->getMessage());
            throw new \RuntimeException('Erreur lors de la récupération de la proposition : ' . $e->getMessage());
        }
    }

    // public static function getAllPropositions(): array
    // {
    //     try {
    //         $service = new PropositionStageService(new PropositionStageRepository());
    //         $service = self::getService();
    //         return $service->getAllPropositions();
    //     } catch (\Throwable $e) {
    //         error_log('Erreur dans getAllPropositions: ' . $e->getMessage());
    //         throw new \RuntimeException('Erreur lors de la récupération des propositions : ' . $e->getMessage());
    //     }
    // }

}



