<?php
//modules/AuthModule/src/Module.php

namespace Modules\AuthModule;

use App\ModuleInterface;
use App\Container;
use App\Router;
use App\EventDispatcher\EventDispatcher;

class Module implements Moduleinterface
{
    public function register (Container $container):void{
        // ex. $container->bind(UserRepository::class, Repository\UserRepository::class);
        
        // 1) Bind du repository et du service
        $container->bind(
            Repository\UserRepository::class,
            function(){
                return new Repository\UserRepository($container->get(\PDO::class));
            }
        );
        $container->bind(
            Service\AuthService::class,
            function($c){
                return new Service\AuthService($c->get(Repository\UserRepository::class));
            }
        );
    }
    public function boot (Router $router, EventDispatcher $dispatcher):void
    {
        // ex. $router->add('GET','/login',fn()=>'Form login');

        // 2) Routes d'authentification
        $router->add('GET','/login',[Controller\AuthController::class,'showLoginForm']);
        $router->add('POST','/login',[Controller\AuthController::class,'login']);
        $router->add('GET','/register',[Controller\AuthController::class,'showRegisterForm']);
        $router->add('POST','/register',[Controller\AuthController::class,'register']);
        $router->add('GET','/logout',[Controller\AuthController::class,'logout']);
        $router->add('GET','/profile',[Controller\AuthController::class,'showProfile']);
        $router->add('GET','/EditAccount',[Controller\AuthController::class,'showEditAccount']);
        $router->add('POST','/EditAccount',[Controller\AuthController::class,'editAccount']);
        $router->add('GET','/candidacy',[Controller\AuthController::class,'showcandidacy']);
        $router->add('GET','/IntershipHistory',[Controller\AuthController::class,'showIntershipHistory']);
        
    }
}