<?php
// src/modules/PropositionStagemodule/Module.php

namespace Modules\PropositionStagemodule;

use App\ModuleInterface;
use App\Container;
use App\Router;
use App\EventDispatcher\EventDispatcher;
use Modules\PropositionStagemodule\Controller\PropositionStageController;

class Module implements ModuleInterface
{
    public function register(Container $container): void
    {
$container->bind(Repository\PropositionStageRepository::class, function(){
    return new Repository\PropositionStageRepository($container->get(\PDO::class));
});
$container->bind(Service\PropositionstageService::class, function(){
    return new Service\PropositionstageService($container->get(Repository\PropositionStageRepository::class));
});
    }

    public function boot(Router $router, EventDispatcher $dispatcher): void
    {
        $router->add('GET','/Accueil', [Controller\PropositionStageController::class, 'index']);
        $router -> add('GET','/proposition_stage', [Controller\PropositionStageController::class, 'proposals']);
    }



}

