<?php
// src/Container.php

declare(strict_types=1);

namespace App;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundexceptionInterface;
use Psr\Container\ContainerExeptionInterface;
use ReflectionClass;
use ReflectionException;
use PDO;

class Container implements ContainerInterface
{
    /** @var array<String>, callable|object>
     */
    private array $bindings = [];

    public function __construct(){
     /**
     * Binding automatique du service PDO** au démarrage du conteneur
     */
        $this->bind(\PDO::class,function(){
            // Lecture des pzrzmètres depuis les variables d'environnement
            $driver = getenv('DB_DRIVER')?:'mysql';
            $host = getenv('DB_HOST')?:'localhost';
            $port = getenv('DB_PORT')?:'3306';
            $db = getenv('DB_DATABASE')?:'stage_manager_app';
            $user = getenv('DB_USERNAME')?:'root';
            $pass = getenv('DB_PASSWORD')?:'';
            // Contruction du DNS
            $dns = "$driver:host=$host;port=$port;dbname=$db;charset=utf8";
            return new \PDO($dns, $user,$pass,[
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    
                \PDO::ATTR_DEFAULT_FETCH_MODE =>
                \PDO::FETCH_ASSOC,

                \PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        });
    
    }

    /**
     * Enregistrer un service sous l'abstraction données
     * 
     * @param string $abstract Clé ou interface
     * @param callable|string|object $concrete Factory, nom de la classe ou instance
     */
    public function bind(string $abstract, $concrete):void{
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Résout et renvoie le service correspondant à l'abstraction.
     * 
     * @param string $id
     * @return mixed
     * @throws ContainerExceptionInterface
     */

     public function get($id){
        if (!isset($this->bindings[$id])) {
            throw new class("Service [$id] non trouvé dans le container")
            extends \Exception implements NotFoundExceptionInterface{};
        }
        $concrete =  $this->bindings[$id];
        
        //si c'est déjà une instance, on la renvoie
        if(!is_string($concrete)&&!is_callable($concrete)){
            return $concrete;
        }

        // Si c'est un nom de classe, on instancie via Reflection
        if(is_string($concrete)){
            try {
                $reflector = new ReflectionClass($concrete);
                return $reflector->newInstance();
            } catch (ReflectionExeption $e) {
                throw new class("impossible d'instancier la classe [$concrete]")
                extends \Exception implements ContainerExceptioninterface{};
            }
        }
        // Si c'est une factory, on l'appelle
        return $concrete($this);
     }

     /**
      * Vérifie si un service existe.
      *
      * @param string $id
      * @param bool
      */
      public function has ($id):bool{
        return isset($this->$bindings[$id]);
      }
}