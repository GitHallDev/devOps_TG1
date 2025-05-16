<?php
// src/EventDispatcher/ListenerProviderInterface.php
namespace App\EventDispatcher;

interface ListenerProviderinterface {
    public function getListenersForEvent(object $event):iterable;
}