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
            header('Location:/Accueil');
            exit;
        }
        $_SESSION['error'] = 'Email ou mot de passe incorrect';
        header('Location:/login');
        exit;
    }


     public static function editAccount():string
    {
        $auth = new AuthService(new \Modules\AuthModule\Repository\AuthRepository());
        $user = $auth->user();
        if($user){
            $userData = [
                'ID_user' => $user['ID_user'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'email' => $_POST['email'],
                'password' => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'],
                'role' => $user['role']
            ];
            $auth->updateAccount($userData);
            header('Location:/profile');
            exit;
        }
        return 'Utilisateur non trouvé';
    }



     public static function showProfile():string
    {
        
        return include __DIR__ .'/../view/profile.php';
    }    



    public static function showEditAccount():string
    {
        return include __DIR__ .'/../view/edit_account.php';
    }


    public static function showIntershipHistory():string
    {
        $auth = new AuthService(new \Modules\AuthModule\Repository\AuthRepository());
        $user = $auth->user();
        
        if (!$user) {
            header('Location:/login');
            exit;
        }

        $stageRepo = new \Modules\AuthModule\Repository\AuthRepository();
        $stages = $stageRepo->findByUserId($user['ID_user']);
        
        return include __DIR__ .'/../view/internship_history.php';
    }


    public static function showcandidacy():string
    {
        $auth = new AuthService(new \Modules\AuthModule\Repository\AuthRepository());
        $user = $auth->user();
        
        if (!$user) {
            header('Location:/login');
            exit;
        }

        $candidatureRepo = new \Modules\AuthModule\Repository\AuthRepository();
        $candidatures = $candidatureRepo->findCandidaturesByUserId($user['ID_user']);

        return include __DIR__ .'/../view/candidacy.php';
    }


    public static function showRegisterForm():string
    {
        return include __DIR__ .'/../View/register.php';
    }

    public static function register():string{
        $auth = new AuthService(new \Modules\AuthModule\Repository\AuthRepository());
        $auth -> register($_POST['email'],$_POST['password'],$_POST['nom'],$_POST['prenom'],'individus');
        header('Location:/login');
        // echo $_POST['role'];
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