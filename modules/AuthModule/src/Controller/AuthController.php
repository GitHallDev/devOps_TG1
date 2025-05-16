<?php
// src/Controller/AuthController.php
namespace Modules\AuthModule\Controller;

use Modules\AuthModule\Service\AuthService;

class AuthController
{
    public static function showLoginForm():String
    {
        return include __DIR__.'/../View/login.php';
    }

    public static function login():string{
        $auth  = new AuthService(new \Modules\AuthModule\Repository\AuthRepository());
        if($auth->attempt($_POST['email'],$_POST['password'])){
            header('Location:/candidater');
            exit;
        }
        return 'Identifiants invalides';
    }

    public static function showRegisterForm():string
    {
        return include __DIR__ .'/../View/register.php';
    }

    public static function register():string{
        $auth = new AuthService(new \Modules\AuthModule\Repository\AuthRepository());
        $auth -> register($_POST['email'],$_POST['password'],$_POST['nom'],$_POST['prenom'],$_POST['role']);
        header('Location:/login');
        exit;
    }
    public static function logout():void
    { 
        $auth = new AuthService(new \Modules\AuthModule\Repository\AuthRepository());
        $auth->logout();
        header('Location:/login');
        exit;
    }
}