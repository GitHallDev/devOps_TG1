<?php
// src/Kernel.php

declare(strict_types=1);

namespace App;
class Kernel
{
    private Container $container;
    private Router $route;
    private EventDispatcher\EventDispatcher $dispatcher;

    /**
     * @param ModuleInterface[] $modules
     */

    public function __construct(array $modules)
    {
        
        $this->container = new Container();
        $this->router = new Router();
        $listenerProvider= new EventDispatcher\ListenerProvider();
        $this->dispatcher = new EventDispatcher\EventDispatcher($listenerProvider);

        // 1) register() de chaque module (binding des services)
        foreach ($modules as $module) {
            $module->register($this->container);
        }

        // 2) Boot() de chaque modules (routes & events)
        foreach ($modules as $module) {
        $module->boot($this->router,$this->dispatcher);
        }
    }

    /**
     * Traiter la requête HTTP et renvoie la réponse.
     */
    public function handle(string $method, string $uri):string
     {

        return $this->router->dispatch($method, $uri);
     }
}