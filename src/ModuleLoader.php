<?php
// src/ModuleLoader.php

declare(strict_types = 1);

namespace App;

use App\Moduleinterface;

class ModuleLoader
{
    /**
     * @param string $modulesDir Cheminabsolu vers le dossier modules/
     */
    public function __construct(string $modulesDir)
    {
        $this->modulesDir = rtrim($modulesDir,'/');
    }

    /**
     * Scanne modules/ et instancie chaque Module.
     * 
     * @return Moduleinterface[]
     */
    public function loadAll():array
    {
        $instances =  [];

        // 1. Liste tous les dossiers dans modules/
        foreach (scandir($this->modulesDir) as $entry) {

            if ($entry ==='.'||$entry==='..') {
                continue;
            }

            $path = $this->modulesDir.'/'.$entry;
            if(! is_dir($path)){
                continue;
            }
            
            // 2. Construit le FQCN du Module selon la convention PSR-4:
            // Namespace racine "Modules\<NomDuDossier>\Module"
            $class = "Modules\\$entry\Module";

            // 3. Vérifie l'existence et l'interface
            if(class_exists($class) && in_array(ModuleInterface::class, class_implements($class)))
            {
                // 4. Instancie et ajoute
                array_push($instances, new $class());
            }
        }

        return $instances;
    }
}