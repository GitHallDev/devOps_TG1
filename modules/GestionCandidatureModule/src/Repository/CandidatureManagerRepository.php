<?php
// src/Repository/CandidatureManagerRepository.php
namespace Modules\GestionCandidatureModule\Repository;
use PDO;
class CandidatureManagerRepository
{
    private PDO $pdo;

    public function __construct(){
        $c = new \App\Container();
        $this->pdo = $c->get(\PDO::class);
    }

    public function getAllCandidature():array
    {
        return $this->pdo->query('SELECT ID_cand, statuts, ID_user, ID_prop, ID_stage FROM candidatures')->fetchAll();  
    }

    public function getCandidatureByProposition(int $id_proposition):array{
        $stmt = $this->pdo->prepare('SELECT * FROM candidatures
                                      WHERE ID_prop = ?
                                    ');
        $stmt->execute([$id_proposition]);
        return $stmt->fetchAll();
    }

    public function get_propositionBycand($id_prop_in_cand){
        $stmt = $this->pdo->prepare('SELECT * from propositions where id_prop = :id');
        $stmt->execute([':id'=>$id_prop_in_cand]);
        
        return $stmt->fetch();
    }

    public function saveCandidature(array $candidature): void
    {
        try {
            // Préparez la requête SQL
            $stmt = $this->pdo->prepare('INSERT INTO candidatures (cv, cover_letter, statuts, id_user, id_prop)
                                         VALUES(:cv, :cover_letter, :statuts, :id_user, :id_prop)');

            // Ouvrez les fichiers en mode lecture binaire
            $cvStream = fopen($candidature['cv'], 'rb');
            $coverLetterStream = fopen($candidature['cover_letter'], 'rb');

            $cvContent = file_get_contents($candidature['cv']);
            $coverLetterContent =file_get_contents($candidature['cover_letter']);

            // Fermez les flux après utilisation
            fclose($cvStream);
            fclose($coverLetterStream);

            // Exécutez la requête en passant les flux
            $stmt->execute([
                ':cv' => $cvContent,
                ':cover_letter' => $coverLetterContent,
                ':statuts' => $candidature['statuts'],
                ':id_user' => $candidature['id_user'],
                ':id_prop' => $candidature['id_prop'],
            ]);


        } catch (\PDOException $e) {
            // Gérer l'erreur
            throw new \RuntimeException('Erreur lors de l\'enregistrement de la candidature : ' . $e->getMessage());
        }
    }

    public function getCandidaturesByUser($id):array{
        $stmt = $this->pdo->prepare('SELECT * FROM candidatures
                                      WHERE id_user = ? 
                                    ');
        $stmt->execute([$id]);
    return        $stmt->fetchAll();}

    public function getCandidatureByID($id_cand):array{
        $stmt = $this->pdo->prepare('SELECT * FROM candidatures
                                      WHERE id_cand = ?
                                    ');
        $stmt->execute([$id_cand]);
        return        $stmt->fetch(); }


    /**
     * Récupère le fichier blob à partir de la base de données et le renvoie au navigateur pour téléchargement.
     *
     * @param int $id_user L'ID de l'utilisateur dont le fichier blob doit être récupéré.
     * @param string $fileType Le type de fichier à récupérer (cv ou cover_letter).
     * @return void
     */
    public function getBlobFile(int $id_user, int $id_cand ,string $fileType): void
    {   
        try {
            // Préparez la requête pour récupérer le champ blob
            $stmt = $this->pdo->prepare("SELECT $fileType FROM candidatures WHERE id_user = :id and id_cand = :id_cand");
            $stmt->execute([':id' => $id_user, ':id_cand' => $id_cand]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "SELECT $fileType FROM candidatures WHERE id_user = $id_user and id_cand = $id_cand";

            if ($result && isset($result[$fileType])) {
                // Définir les en-têtes pour le téléchargement
                $content = $result[$fileType];
                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="' . $fileType . '_ID_USER=' . $id_user . '.pdf"');
                header('Content-Transfer-Encoding: binary');
                header('Accept-Ranges: bytes');
                echo $content;
                exit;
            } else {
                throw new \RuntimeException('Fichier introuvable ou champ blob vide.');
            }
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erreur lors de la récupération du fichier blob : ' . $e->getMessage());
        }
    }

}