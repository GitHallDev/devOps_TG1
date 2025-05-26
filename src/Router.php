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

        // Vérifier d'abord les correspondances exactes
        if (isset($this->routes[$method][$path])) {
            return (string) call_user_func($this->routes[$method][$path]);
        }

        // Vérifier les routes avec paramètres
        foreach ($this->routes[$method] as $route => $handler) {
            // Convertir les paramètres {param} en expression régulière
            if (strpos($route, '{') !== false) {
                $pattern = preg_replace('/{([^}]+)}/', '([^/]+)', $route);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $path, $matches)) {
                    // Extraire les valeurs des paramètres
                    array_shift($matches); // Supprimer la correspondance complète
                    
                    // Extraire les noms des paramètres
                    preg_match_all('/{([^}]+)}/', $route, $paramNames);
                    $params = array_combine($paramNames[1], $matches);
                    
                    // Stocker les paramètres pour qu'ils soient accessibles dans le gestionnaire
                    $_GET = array_merge($_GET, $params);
                    
                    return (string) call_user_func($handler);
                }
            }
        }

        http_response_code(404);
        return '404 Not Found';
      }
}