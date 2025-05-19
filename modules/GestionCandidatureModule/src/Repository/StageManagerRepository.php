<?php
// src/Repository/StageManagerRepository.php
namespace Modules\GestionCandidatureModule\Repository;
use PDO;
use \DateTime;

class StageManagerRepository{

    private PDO $pdo;

    public function __construct(){
     $c = new \App\Container();
     $this->pdo = $c->get(\PDO::class);   
    }

    public function create_stage_effectif(?int $id_cand, int $id_user, DateTime $start_date, DateTime $end_date, string $sujet_effectif, int $remuneration_effective, string $statuts, string $encadreur):void{
    try {
        $stmt=$this->pdo->prepare('INSERT INTO stages (Debut_stage, Fin_stage, Encadreur, Sujet_effectif, Remuneration_effective, statuts, id_user)
                                    VALUES (:Debut_stage, :Fin_stage, :Encadreur, :Sujet_effectif, :Remuneration_effective, :statuts, :id_user)');
        $stmt->execute([
            ':Debut_stage' => $start_date->format('Y-m-d H:i:s'),
            ':Fin_stage' => $end_date->format('Y-m-d H:i:s'),
            ':Encadreur' => $encadreur,
            ':Sujet_effectif' => $sujet_effectif,
            ':Remuneration_effective' => $remuneration_effective,
            ':statuts' => $statuts,
            ':id_user' => $id_user
        ]);
        if($id_cand != null)
            $stmt = $this->pdo->prepare('UPDATE candidatures SET statuts = :statuts WHERE ID_cand = :id_cand');
            $stmt->execute([
                ':statuts' => 'accepter',
                ':id_cand' => $id_cand
            ]);

            $stmt = $this->pdo->prepare('UPDATE candidatures SET ID_stage = LAST_INSERT_ID() WHERE ID_cand = :id_cand');
            $stmt->execute([
                ':id_cand' => $id_cand
            ]);

        }catch(\PDOException $e){
            // Gérer l'exception PDO
            throw new \Exception("Erreur lors de la création d'un stage effectif : ".$e->getMessage());
        } catch (\Throwable $th) {
            // Gérer les autres exceptions
        throw new \RuntimeException('Erreur inattendue : ' . $th->getMessage());
        }
    }
}
