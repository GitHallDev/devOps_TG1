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
        $stmt = $this->pdo->prepare('SELECT * FROM t1_users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch()?:null; 
    }

    public function save(array $user):void
    {
        $stmt = $this->pdo->prepare('
        INSERT INTO t1_users (nom, prenom, email, password, role) 
        VALUES (:nom, :prenom, :email, :password, :role)
        ');
        $stmt->execute([':nom' => $user['nom'],
                        ':prenom' => $user['prenom'],
                        ':email' => $user['email'],
                        ':password' => $user['password'],
                        ':role' => $user['role'],
    ]);
    }


    
    public function update(array $user):void
    {
        $stmt = $this->pdo->prepare('
        UPDATE t1_users 
        SET nom = :nom, prenom = :prenom, email = :email, password = :password
        WHERE ID_user = :ID_user
        ');
        $stmt->execute([
            ':ID_user' => $user['ID_user'],
            ':nom' => $user['nom'],
            ':prenom' => $user['prenom'],
            ':email' => $user['email'],
            ':password' => $user['password']
        ]);
    }

    
    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT * FROM t1_stages 
            WHERE ID_user = ? 
            ORDER BY Debut_stage DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll() ?: [];
    }

    public function findCandidaturesByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare('
            SELECT c.*, p.sujet, p.Duree, p.remuneration, p.statuts as proposition_statut
            FROM t1_candidatures c
            JOIN t1_propositions p ON c.ID_prop = p.ID_prop
            WHERE c.ID_user = ?
            ORDER BY c.ID_cand DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll()?:[];
    }
}