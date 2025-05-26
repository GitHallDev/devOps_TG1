<?php
// src/Service/AutuhService.php
namespace Modules\AuthModule\Service;

use Modules\AuthModule\Repository\AuthRepository;

class AuthService 
{
    private AuthRepository $repo;

    public function __construct(AuthRepository $repo){
        $this->repo = $repo;
        session_start();
    }

    public function attempt(string $email, string $password):bool{
        $user = $this->repo->findByEmail($email);
        if($user && password_verify($password, $user['password']))
        {
            $_SESSION['user']=$user;
            return true;
        }
        return false;
    }

    public function register( string $email, string $password, string $nom, string $prenom, string $role ):void
    {
        $hash = password_hash($password,PASSWORD_BCRYPT);
        $this->repo->save(['email'=>$email,'password'=>$hash,'nom'=>$nom, 'prenom'=>$prenom, 'role'=>$role]);
    }

    public function logout():void
    {
        session_destroy();
    }

    
    public function updateAccount(array $userData): void
    {
        $this->repo->update($userData);
        $_SESSION['user'] = $userData;
    }

    
    public function user():?array
    {
        return $_SESSION['user']??null;
    }
}