<?php
// public/index.php

declare(strict_types=1);

// 1) Chargement de l'autoloader PSR-4 généré par Composer
require __DIR__.'/../vendor/autoload.php';

// 2) Découvrire et instanciation des modules

// - ModuleLoader prend en paramètre le dossier modules/
// - Il doit retourner un tableau d'instances implémentant ModuleInterface
$loader = new \App\ModuleLoader(__DIR__.'/../modules');
$modules = $loader->loadAll();

// 3) Charger les données d'environnement
 Dotenv\Dotenv::createImmutable(__DIR__.'/../')->load();

// 4) Gestion CORS
// require __DIR__.'/../src/Cors.php';
// cors();
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header('Access-Control-Allow-Headers: X-Requested-Width');
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// 5) Démarrage du Kernel
// - Le Kernel orchestre l'appel à register() et boot() de chaque module
$kernel = new \App\Kernel($modules);

// 6) Traitement de la requête HTTP
// - handle() doit router la requête ($_SERVER['REQUEST_METHOD'],$_SERVER['REQUEST_URI'])
$response = $kernel->handle($_SERVER['REQUEST_METHOD'],$_SERVER['REQUEST_URI']);

// 7) Envoi de la réponse au client
// echo $response;

 