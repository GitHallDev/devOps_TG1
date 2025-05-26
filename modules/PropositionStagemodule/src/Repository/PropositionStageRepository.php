<?php
// src/modules/PropositionStagemodule/Repository/PropositionStageRepository.php
namespace Modules\PropositionStagemodule\Repository;

use PDO;
use App\MailService;

class PropositionStageRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM t1_propositions ORDER BY ID_prop DESC');
        return $stmt->fetchAll();
    }

    public function findAllAccepted(): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM t1_propositions WHERE statuts= :statuts AND affecter= :afectter ORDER BY ID_prop DESC');
        $stmt->bindValue(':statuts', 'accepter', PDO::PARAM_STR);
        $stmt->bindValue(':affecter',0, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findLatest(int $limit = 10): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM t1_propositions WHERE statuts= :statuts AND affecter= :affecter ORDER BY ID_prop DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':statuts','accepter', PDO::PARAM_STR);
        $stmt->bindValue(':affecter',0, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // integration partie Salamata

     /**
     * Enregistre une nouvelle proposition de stage.
     *
     * @param array $propositon
     * @return void
     */
    public function saveProposition(array $propositon): void
    {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO t1_propositions (sujet, Duree, remuneration, statuts, create_by)
                 VALUES (:sujet, :Duree, :remuneration, :statuts, :create_by)'
            );

            $stmt->execute([
                ':sujet' => $propositon['sujet'],
                ':Duree' => $propositon['Duree'],
                ':remuneration' => $propositon['remuneration'],
                ':statuts' => $propositon['statuts'],
                ':create_by' => $propositon['create_by'],
            ]);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erreur lors de l\'enregistrement de la proposition : ' . $e->getMessage());
        }
    }

    /**
     * Met à jour une proposition existante.
     *
     * @param int $id L'ID de la proposition à mettre à jour.
     * @param array $proposition Les nouvelles données à enregistrer.
     * @return void
     */
    public function updateProposition(int $id, array $proposition): void
    {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE t1_propositions
                 SET sujet = :sujet, Duree = :Duree, remuneration = :remuneration, create_by = :create_by
                 WHERE ID_prop = :id'
            );

            $stmt->execute([
                ':sujet' => $proposition['sujet'],
                ':Duree' => $proposition['Duree'],
                ':remuneration' => $proposition['remuneration'],
                ':create_by' => $proposition['create_by'],
                ':id' => $id
            ]);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erreur lors de la mise à jour de la proposition : ' . $e->getMessage());
        }
    }

    /**
     * Supprime une proposition de stage.
     *
     * @param int $id L'ID de la proposition à supprimer.
     * @return bool
     */
    public function deleteProposition(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM t1_propositions WHERE ID_prop = :id');
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erreur lors de la suppression de la proposition : ' . $e->getMessage());
        }
    }


        /**
     * Met à jour uniquement le statut d'une proposition de stage.
     *
     * @param int $id L'ID de la proposition à modifier.
     * @param string $nouveauStatut Le nouveau statut (ex: "accepté", "refusé").
     * @return void
     */
    public function updateStatutProposition(int $id, string $nouveauStatut): void
    {
        try {
            $stmt = $this->pdo->prepare(
                'UPDATE t1_propositions SET statuts = :statuts WHERE ID_prop = :id'
            );

            $stmt->execute([
                ':statuts' => $nouveauStatut,
                ':id' => $id
            ]);

            $mailService = new MailService($this->pdo);
            $mailService->envoyerNotification('propositions', $id, $nouveauStatut);
        } catch (\PDOException $e) {
            throw new \RuntimeException('Erreur lors de la mise à jour du statut : ' . $e->getMessage());
        }
    }


        /**
     * Récupère une proposition par son ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getPropositionById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT ID_prop, sujet, Duree, remuneration, statuts, create_by FROM t1_propositions WHERE ID_prop = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }


}