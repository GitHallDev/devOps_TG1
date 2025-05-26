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

        //  integreation Salamata partie

                // Soumettre le formulaire de création
        $router->add('POST', '/proposition/create', [Controller\PropositionStageController::class, 'saveProposition']);

        // Modifier une proposition
        $router->add('POST', '/proposition/update', [Controller\PropositionStageController::class, 'updateProposition']);

        // Changer le statut (accepter/refuser)
        $router->add('POST', '/proposition/statuts', [Controller\PropositionStageController::class, 'updateStatutProposition']);

        // Supprimer une proposition
        $router->add('POST', '/proposition/delete', [Controller\PropositionStageController::class, 'deleteProposition']);

        // Afficher le de suppression
        $router->add('GET', '/PropositionDelete', [Controller\PropositionStageController::class, 'showDeleteProposition']);

        // Afficher le formulaire de création
        $router->add('GET', '/propositionCreate', [Controller\PropositionStageController::class, 'showCreateProposition']);

        // Tableau des propositions
        $router->add('GET', '/PropositionBoard', [Controller\PropositionStageController::class, 'PropositionBoard']);
        
        // Formulaire d'édition
        // $router->add('GET', '/PropositionEditForm', [Controller\PropositionStageController::class, 'showEditionPrositionForm']);
        $router->add('GET', '/PropositionEditForm', [Controller\PropositionStageController::class, 'showEditionPropositionForm']);
        
        // Formulaire de changement de statut
        $router->add('GET', '/PropositionChangeStatusForm', [Controller\PropositionStageController::class, 'showChangeStatusProposition']);

        // Formulaire de suppresion d'un proposition de stage
        $router->add('GET','/DeletePropositionForm',[Controller\PropositionStageController::class, 'showDeleteProposition']);
    }



}

