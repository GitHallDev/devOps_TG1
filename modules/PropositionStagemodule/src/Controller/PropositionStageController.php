<?php
// src/modules/PropositionStagemodule/Controller/PropositionStageController.php
namespace Modules\PropositionStagemodule\Controller;

use App\Controller;
use App\Container;
use Modules\PropositionStagemodule\Service\PropositionStageService; 
    
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

    public static function getLatestPropositions(int $limit = 10):array
    {
        return self::getService()->getLatestPropositions($limit);
    }
}



