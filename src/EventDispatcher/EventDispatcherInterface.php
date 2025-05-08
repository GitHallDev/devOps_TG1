<?php
// src/EventDispatcher/EventDispatcherInterface.php
namespace App\EventDispatcher;

interface EventDispatcherInterface{
    public function dispatch(object $event):object;
}