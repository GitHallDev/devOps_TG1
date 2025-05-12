<?php
// src/Repository/AuthRepository.php
namespace Modules\AuthModule\Repository;
use PDO;

class AuthRepository
{
    private PDO $pdo;

    public function __construct(){
        $c = new \App\Container(); 
        $this->pdo = $c->get(\PDO::class);
    }

    public function findByEmail(string $email):?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch()?:null; 
    }

    public function save(array $user):void
    {
        $stmt = $this->pdo->prepare('
        INSERT INTO users (nom, prenom, email, password, role) 
        VALUES (:nom, :prenom, :email, :password, :role)
        ');
        $stmt->execute([':nom' => $user['nom'],
                        ':prenom' => $user['prenom'],
                        ':email' => $user['email'],
                        ':password' => $user['password'],
                        ':role' => $user['role'],
    ]);
    }
}