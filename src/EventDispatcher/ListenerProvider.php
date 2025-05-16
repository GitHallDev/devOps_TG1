<?php
// src/EventDispatcher/ListenerProvider.php

namespace App\EventDispatcher;

class ListenerProvider implements ListenerProviderInterface
{
    /**
     * @var array $listners 
     */
    private array $listeners = [];

    /**
     * @param string $eventType 
     * @param callable $listener 
     */
    public function addListener(string $eventType, callable $listener):void
    {
        $this->listeners[$eventType][]=$listener;
    }

    /**
     * Recupère les listener en fonction de l'évènement
     * @param object $event
     * @return iterable
     */
    public function getListenersForEvent(object $event):iterable
    {
        $eventType = get_class($event);
        return $this->$listeners[$eventType]??[];
    }
}