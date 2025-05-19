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
    }

            public function boot(Router $router, EventDispatcher $dispatcher):void{
            // $router->add('GET','/candidatures',[Controller.CandidatureManager::class,'showCandidaturesPage']);

            $router->add('GET','/candidater',[Controller\CandidatureManagerController::class,'showCandidatureForm']); /* ex. /candidater?id_prop=1 */
            $router->add('POST','/candidater',[Controller\CandidatureManagerController::class,'candidater']);
            $router->add('GET','/candidatures',[Controller\CandidatureManagerController::class,'pageCandidatures']);
            $router->add('GET','/candidatureCV',[Controller\CandidatureManagerController::class,'getCandidatureCVById']); /* ex. /candidatureCV?id=1 */
            $router->add('GET','/candidatureLM',[Controller\CandidatureManagerController::class,'getCandidatureLMById']); /* ex. /candidatureLM?id=1 */
            $router->add('GET','/propositionByCand',[Controller\CandidatureManagerController::class,'get_proposition_by_candidature']); /*ex. /propositionByCand?id=6 */
            $router->add('POST','/createStageEffectif',[Controller\StageManagerController::class,'createStageEffectif']); /*ex. /createStageEffectif */
            $router->add('GET','/stages',[Controller\StageManagerController::class,'showStageEffectifPage']); /*ex. /stages */
        }
}