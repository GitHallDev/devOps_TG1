<?php
// src/ModuleInterface.php

declare(strict_types = 1);

namespace App;

use App\EventDispatcher\EventDispatcher;
interface Moduleinterface
{
    /**
     * Enregistre les services du module dans le container.
     * 
     * @param Container $container
     */
    public function register(Container $container):void;

    /**
     * Déclare routes et listeners d'évènements après enregistrement.
     * 
     * @param Router $router
     * @param EventDispatcher $dispatcher
     */
     public function boot(Router $router, EventDispatcher $dispatcher):void;
    }