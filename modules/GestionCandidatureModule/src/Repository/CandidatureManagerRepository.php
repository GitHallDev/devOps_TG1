<?php
// src/Repository/CandidatureManagerRepository.php
namespace Modules\GestionCandidatureModule\Repository;
use PDO;
class CandidatureManagerRepository
{
    /**
     * @var PDO $pdo connection avec la BDD
     */
    private PDO $pdo;


    /**
     * Constructeur de la classe
     */
    public function __construct(){
        $c = new \App\Container();
        $this->pdo = $c->get(\PDO::class);
    }

    /**
     * Récupère toutes les propositions les candidatures
     * 
     * @return array
     */
    public function getAllCandidature():array
    {
        return $this->pdo->query('SELECT ID_cand, statuts, ID_user, ID_prop, ID_stage FROM t1_candidatures')->fetchAll();  
    }

    /**
     * Recupère les candidatures en fonction de l'id d'une proposition
     * 
     * @param int $id_proposition 
     * @return array
     */
    public function getCandidatureByProposition(int $id_proposition):array{
        $stmt = $this->pdo->prepare('SELECT * FROM t1_candidatures
                                      WHERE ID_prop = ?
                                    ');
        $stmt->execute([$id_proposition]);
        return $stmt->fetchAll();
    }

    /**
     * Recupère la proposition en fonction de l'id de la proposition la table candidature
     * 
     * @param int $id_prop_in_cand l'id de la proposition dans la table candidature
     * @return array
     */
    public function get_propositionBycand(int $id_prop_in_cand):array{
      try {
        $stmt = $this->pdo->prepare('SELECT * from t1_propositions where id_prop = :id');
        $stmt->execute([':id'=>$id_prop_in_cand]);
        $proposition = $stmt->fetch();
        // echo "<pre>";
        // print_r($stmt->fetch());
        // echo"<\pre>";

        return $proposition;
      } catch (\PDOException $e) {
        throw new \PDOException('Erreur lors de la récupération de la proposition : ' . $e->getMessage());
      } catch (\Throwable $th) {
        //throw new 
        throw new \RuntimeException('Erreur inattendue : ' . $th->getMessage());
      }
    }

    /**
     * Sauvegarde la candidature dans la base de donnée
     * 
     * @param array $candiature candidature sous forme de dictionnaire
     * @return void
     * @throws \RuntimeException
     */

    public function saveCandidature(array $candidature): void
    {
        try {
            // Créer le dossier de stockage s'il n'existe pas
            $uploadDir = __DIR__ . '/../../../../public/uploads/candidatures/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Générer des noms de fichiers uniques
            $cvFileName = uniqid('cv_') . '_' . basename($candidature['cv']);
            $coverLetterFileName = uniqid('cl_') . '_' . basename($candidature['cover_letter']);

            // Déplacer les fichiers vers le dossier de stockage
            $cvPath = $uploadDir . $cvFileName;
            $coverLetterPath = $uploadDir . $coverLetterFileName;

            copy($candidature['cv'], $cvPath);
            copy($candidature['cover_letter'], $coverLetterPath);

            // Sauvegarder les chemins dans la base de données
            $stmt = $this->pdo->prepare('INSERT INTO t1_candidatures (cv, cover_letter, statuts, id_user, id_prop)
                                         VALUES(:cv, :cover_letter, :statuts, :id_user, :id_prop)');

            $stmt->execute([
                ':cv' => $cvPath,
                ':cover_letter' => $coverLetterPath,
                ':statuts' => $candidature['statuts'],
                ':id_user' => $candidature['id_user'],
                ':id_prop' => $candidature['id_prop'],
            ]);

        } catch (\PDOException $e) {
            throw new \RuntimeException('Erreur lors de l\'enregistrement de la candidature : ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erreur lors de l\'enregistrement de la candidature : ' . $e->getMessage());
        }
    }


    /**
     * Récupèrer la candidature en fonction de l'id de son utilisateur
     * 
     * @param int $id l'id de l'utilisateur à qui appartient la candidature
     * @return array
     * @throws \RuntimeException
     */

    public function getCandidaturesByUser(int $id):array{
        try {
             $stmt = $this->pdo->prepare('SELECT * FROM t1_candidatures
                                      WHERE id_user = ? 
                                    ');
             $stmt->execute([$id]);
             return $stmt->fetchAll();
        } catch (\PDOException $e) {
            // Gérer l'erreur
            throw new \RuntimeException('Erreur lors de la récupération des candidatures : ' . $e->getMessage());
        } catch (\Throwable $th) {
            // Gérer les autres erreurs
            throw new \RuntimeException('Erreur inattendue : ' . $th->getMessage());
        }
    }


    /**
    * Récupère la candidature à partir de son ID
    *
    * @param int $id_cand L'id de la candidature à récupérer
    * @return array
    * @throws \RuntimeException
    */
    public function getCandidatureByID(int $id_cand):array{
        try {
                    $stmt = $this->pdo->prepare('SELECT * FROM t1_candidatures
                                      WHERE id_cand = ?
                                    ');
        $stmt->execute([$id_cand]);
        return        $stmt->fetch();
        } catch (\PDOException $e) {
            // Gérer l'erreur
            throw new \RuntimeException('Erreur lors de la récupération de la candidature : ' . $e->getMessage());
        } catch (\Throwable $th) {
            // Gérer les autres erreurs
            throw new \RuntimeException('Erreur inattendue : ' . $th->getMessage());
        } 
    }


    /**
     * Récupère le fichier blob à partir de la base de données et le renvoie au navigateur pour lecture.
     *
     * @param int $id_user L'ID de l'utilisateur dont le fichier blob doit être récupéré.
     * @param string $fileType Le type de fichier à récupérer (cv ou cover_letter).
     * @param int $id_cand l'id de la candidature dont le fichier blob doit être récupéré.
     * @return void
     * @throws \RuntimeException
     * @throws \PDOException
     */
    public function getBlobFile(int $id_user, int $id_cand, string $fileType): void
    {   
        try {
            // Récupérer le chemin du fichier depuis la base de données
            $stmt = $this->pdo->prepare("SELECT $fileType FROM t1_candidatures WHERE id_user = :id and id_cand = :id_cand");
            $stmt->execute([':id' => $id_user, ':id_cand' => $id_cand]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && isset($result[$fileType]) && file_exists($result[$fileType])) {
                $filePath = $result[$fileType];
                $content = file_get_contents($filePath);
                
                // Définir les en-têtes pour le téléchargement
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                echo $content;
                exit;
            } else {
                throw new \RuntimeException('Fichier introuvable.');
            }
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erreur lors de la récupération du fichier : ' . $e->getMessage());
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erreur inattendue : ' . $e->getMessage());
        }
    }


    /**
     * Supprime une candidature de la base de données en fonction de son identifiant.
     *
     * @param int $id_cand L'identifiant de la candidature à supprimer.
     * @throws \RuntimeException Si une erreur survient lors de la suppression.
     */
    public function deleteCandidature(int $id_cand):bool{
        try {
            // Récupérer les chemins des fichiers avant la suppression
            $stmt = $this->pdo->prepare('SELECT cv, cover_letter FROM t1_candidatures WHERE id_cand = ?');
            $stmt->execute([$id_cand]);
            $files = $stmt->fetch(PDO::FETCH_ASSOC);

            // Supprimer les fichiers physiques s'ils existent
            if ($files) {
                if (file_exists($files['cv'])) {
                    unlink($files['cv']);
                }
                if (file_exists($files['cover_letter'])) {
                    unlink($files['cover_letter']);
                }
            }

            // Supprimer l'enregistrement de la base de données
            $stmt = $this->pdo->prepare('DELETE FROM t1_candidatures WHERE id_cand = ?');
            $stmt->execute([$id_cand]);
            return true;
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erreur lors de la suppression de la candidature : ' . $e->getMessage());
        } catch (\Throwable $th) {
            throw new \RuntimeException('Erreur inattendue : ' . $th->getMessage());
        }
    }

}