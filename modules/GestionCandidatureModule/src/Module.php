<?php
// modules/GestionCandidatureModule/src/Module.php
namespace Modules\GestionCandidatureModule;

use App\ModuleInterface;
use App\Container;
use App\Router;
use App\EventDispatcher\EventDispatcher;

class Module implements ModuleInterface
{
    public function register(Container $container):void{

        $container->bind(
            Repository\CandidatureManagerRepository::class,
            function(){
                return new Repository\CandidatureManagerRepository($container->get(\PDO::class));
            }
        );

        $container->bind(
            Service\CandidatureManagerService::class,
            function($c){
                return new Service\CandidatureManagerService ($c->get(Repository\CandidatureManageRepsitory::class));
            }
        );

        $container->bind(
            Service\StageManagerRepositoryService::class,
            function($c){
                return new Service\StageManagerService($c->get(Repository\ServiceManagerREpository::class));
            }
        );

        $container->bind(
            Repository\SoutenanceRepository::class,
            function() {
                return new Repository\SoutenanceRepository();
            }
        );

        $container->bind(
            Service\SoutenanceService::class,
            function($c) {
                return new Service\SoutenanceService($c->get(Repository\SoutenanceRepository::class));
            }
        );
    }

            public function boot(Router $router, EventDispatcher $dispatcher):void{
            // $router->add('GET','/candidatures',[Controller.CandidatureManager::class,'showCandidaturesPage']);

            $router->add('GET','/candidater',[Controller\CandidatureManagerController::class,'showCandidatureForm']); /* ex. /candidater?id_prop=1 */
            $router->add('POST','/candidater',[Controller\CandidatureManagerController::class,'candidater']);
            $router->add('GET','/candidatures',[Controller\CandidatureManagerController::class,'pageCandidatures']);
            $router->add('GET','/candidatureCV',[Controller\CandidatureManagerController::class,'getCandidatureCVById']); /* ex. /candidatureCV?id=1 */
            $router->add('GET','/candidatureLM',[Controller\CandidatureManagerController::class,'getCandidatureLMById']); /* ex. /candidatureLM?id=1 */
            $router->add('GET','/propositionByCand',[Controller\CandidatureManagerController::class,'get_proposition_by_candidature']); /*ex. /propositionByCand?id=6 */
            $router->add('POST','/deleteCandidature',[Controller\CandidatureManagerController::class,'deleteCandidature']); /*ex. /deleteCandidature */

            // Page de gestion des stages
            $router->add('POST','/createStageEffectif',[Controller\StageManagerController::class,'createStageEffectif']); /*ex. /createStageEffectif */
            $router->add('GET','/Stages',[Controller\StageManagerController::class,'showStageEffectifPage']); /*ex. /stages */
            $router->add('GET','/redirectionStage',[Controller\StageManagerController::class,'showREdirectionPage']);
            $router->add('GET','/planifierSoutenance',[Controller\StageManagerController::class,'showPlanifierSoutenanceForm']);
            $router->add('GET','/Soutenances',[Controller\StageManagerController::class,'showSoutenancesPage']); /*ex. /Soutenances */
            
            // Routes pour les soutenances
            $router->add('GET','/api/soutenances',[Controller\SoutenanceController::class,'getAllSoutenances']);
            $router->add('GET','/api/soutenances/{id}',[Controller\SoutenanceController::class,'getSoutenanceById']);
            $router->add('PUT','/api/soutenances/{id}/status',[Controller\SoutenanceController::class,'updateSoutenanceStatus']);
            // Nouvelles routes à ajouter
            $router->add('POST','/api/soutenances',[Controller\SoutenanceController::class,'createSoutenance']);
            $router->add('PUT','/api/soutenances/{id}',[Controller\SoutenanceController::class,'updateSoutenance']);
            $router->add('DELETE','/api/soutenances/{id}',[Controller\SoutenanceController::class,'deleteSoutenance']);

            // Route pourtester l'envoie du mail
            $router->add('GET','/api/sendmail',[Controller\CandidatureManagerController::class,'sendMailNotification']);
        }
}