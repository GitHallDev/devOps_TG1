<?php
// src/Router.php

declare(strict_types=1);
namespace App;

class Router
{
    /** @var array<string>, array<string,callabre>>
     */
    private array $routes = [];

    /**
     * Ajoute une route.
     * 
     * @param string $method Méthode HTTP (GET,POST...)
     * @param string $path URI (ex. "/login")
     * @param callable $handler Fonction qui renvoie la réponse
     */

     public function add(string $method, string $path, callable $handler): void 
     {
        $method = strtoupper($method);
        $this->routes[$method][$path]= $handler;
     }

     /**
      * Tente d'exécuter la route correspondant à la requête.
      *
      * @param string $method
      * @param string $uri
      * @return string
      */
      public function dispatch(string $method, string $uri):string
      {
        $method = strtoupper($method);
        $path = parse_url($uri, PHP_URL_PATH)?:'/';

            if (isset($this->routes[$method][$path])) {
                return (string) call_user_func($this->routes[$method][$path]);
            }
            http_response_code(404);
            return '404 Not Found';
      }
}