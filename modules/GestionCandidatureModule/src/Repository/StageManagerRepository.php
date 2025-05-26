<?php
// src/Repository/StageManagerRepository.php
namespace Modules\GestionCandidatureModule\Repository;
use PDO;
use \DateTime;
use App\MailService;


class StageManagerRepository{

    private PDO $pdo;

    public function __construct(){
     $c = new \App\Container();
     $this->pdo = $c->get(\PDO::class);   
    }

    public function create_stage_effectif(?int $id_cand, int $id_user, DateTime $start_date, DateTime $end_date, string $sujet_effectif, int $remuneration_effective, string $statuts, string $encadreur):void{
    try {
                
        // modifier l'affectation de la proposition
        // Met à jour la proposition liée à la candidature
        $stmt = $this->pdo->prepare('UPDATE t1_propositions SET affecter = 1 WHERE id_prop = (SELECT id_prop FROM t1_candidatures WHERE ID_cand = :id_cand)');
        $stmt->execute([':id_cand' => $id_cand]);

        $stmt=$this->pdo->prepare('INSERT INTO t1_stages (Debut_stage, Fin_stage, Encadreur, Sujet_effectif, Remuneration_effective, statuts, id_user)
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
        if($id_cand != null){
             $stmt = $this->pdo->prepare('UPDATE t1_candidatures SET statuts = :statuts WHERE ID_cand = :id_cand');
            $stmt->execute([
                ':statuts' => 'accepter',
                ':id_cand' => $id_cand
            ]);

            $stmt = $this->pdo->prepare('UPDATE t1_candidatures SET ID_stage = LAST_INSERT_ID() WHERE ID_cand = :id_cand');
            $stmt->execute([
                ':id_cand' => $id_cand
            ]);
        }
        $mailService = new MailService($this->pdo);
        $mailService->envoyerNotification('candidatures', $id_cand, 'accepter');
        }catch(\PDOException $e){
            // Gérer l'exception PDO
            throw new \Exception("Erreur lors de la création d'un stage effectif : ".$e->getMessage());
        } catch (\Throwable $th) {
            // Gérer les autres exceptions
        throw new \RuntimeException('Erreur inattendue : ' . $th->getMessage());
        }
    }

    public function getAllStage():array{
        try {
            $stmt = $this->pdo->prepare('
                SELECT s.*, u.nom as user_nom, u.prenom as user_prenom 
                FROM t1_stages s
                LEFT JOIN t1_users u ON s.id_user = u.id_user
            ');
            $stmt->execute();
            $stages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $stages;
        }catch(\PDOException $e){
            throw new \PDOException("Erreur lors de la récupération des stages". $e->getMessage());
            
        } catch (\Throwable $th) {
            throw new \RuntimeException("Erreur innatendue : " . $th->getMessage());
            
        }
    }

    public function getAllStageEnCours():array{
        try {
            $stmt= $this->pdo->prepare(" SELECT s.*, u.nom as user_nom, u.prenom as user_prenom 
                                         FROM t1_stages s
                                         LEFT JOIN t1_users u ON s.id_user = u.id_user WHERE statuts = 'en_cours'");
            $stmt->execute();
            $stages = $stmt->fetchAll();
            return $stages;
        }catch(\PDOException $e){
            throw new \PDOException("Erreur lors de la récupération des stages". $e.getMessage());
            
        } catch (\Throwable $th) {
            throw new \RuntimeException("Erreur innatendue : " . $th.getMessage());
            
        }
    }


     public function get_stage_effectif_by_id(int $id): ?array {
        try {
            // Débogage de l'ID reçu
            error_log("Recherche du stage avec ID : " . $id);
            
            $stmt = $this->pdo->prepare('
                SELECT 
                    s.ID_stage,
                    s.Debut_stage,
                    s.Fin_stage,
                    s.Encadreur,
                    s.Sujet_effectif,
                    s.Remuneration_effective,
                    s.statuts,
                    s.ID_user,
                    u.nom as user_nom,
                    u.prenom as user_prenom
                FROM t1_stages s
                LEFT JOIN t1_users u ON s.ID_user = u.id_user 
                WHERE s.ID_stage = :id
            ');
            
            $stmt->execute([':id' => $id]);
            $stage = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Débogage du résultat
            error_log("Résultat de la requête SQL : " . print_r($stage, true));
            
            return $stage ?: null;
        } catch(\PDOException $e) {
            error_log("Erreur PDO lors de la récupération du stage : " . $e->getMessage());
            throw new \PDOException("Erreur lors de la récupération du stage : " . $e->getMessage());
        } catch (\Throwable $th) {
            error_log("Erreur inattendue lors de la récupération du stage : " . $th->getMessage());
            throw new \RuntimeException("Erreur inattendue : " . $th->getMessage());
        }
    }


    public function update_stage_effectif(int $id, ?int $id_cand, int $id_user, DateTime $start_date, DateTime $end_date, string $sujet_effectif, int $remuneration_effective, string $statuts, string $encadreur): bool {
        try {
            error_log("Mise à jour du stage ID : " . $id);
            
            $stmt = $this->pdo->prepare('
                UPDATE t1_stages 
                SET Sujet_effectif = :sujet_effectif,
                    Encadreur = :encadreur,
                    statuts = :statuts
                WHERE ID_stage = :id
            ');
            
            $result = $stmt->execute([
                ':sujet_effectif' => $sujet_effectif,
                ':encadreur' => $encadreur,
                ':statuts' => $statuts,
                ':id' => $id
            ]);
            
            error_log("Résultat de la mise à jour : " . ($result ? "succès" : "échec"));
            
            return $result;
        } catch(\PDOException $e) {
            error_log("Erreur PDO lors de la mise à jour du stage : " . $e->getMessage());
            throw new \PDOException("Erreur lors de la mise à jour du stage : " . $e->getMessage());
        } catch (\Throwable $th) {
            error_log("Erreur inattendue lors de la mise à jour du stage : " . $th->getMessage());
            throw new \RuntimeException("Erreur inattendue : " . $th->getMessage());
        }
    }


    public function getStageById(int $id):array{
        try {
            $stmt= $this->pdo->prepare('SELECT * from t1_stages where id_stage = :id');
            $stmt->execute([':id'=>$id]);
            $stages = $stmt->fetch();
            return $stages;
        }catch(\PDOException $e){
            throw new \PDOException("Erreur lors de la récupération du stage :". $e.getMessage());
        } catch (\Throwable $th) {
             throw new \RuntimeException('Erreur inattendue : ' . $th->getMessage());
        }
    }

    public function deleteStage(int $id):bool{
        try {
            $stmt= $this->pdo->prepare('DELETE from t1_stages where id_stage = :id');
            return $stmt->execute([':id'=>$id]);
        }catch(\PDOException $e){
            throw new \PDOException("Erreur lors de la suppression des du stage :". $e.getMessage());
            
        } catch (\Throwable $th) {
             throw new \RuntimeException('Erreur inattendue : ' . $th->getMessage());
        }
    }

}