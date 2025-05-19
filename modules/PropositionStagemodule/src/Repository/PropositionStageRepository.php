<?php
// src/modules/PropositionStagemodule/Repository/PropositionStageRepository.php
namespace Modules\PropositionStagemodule\Repository;

use PDO;

class PropositionStageRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM propositions ORDER BY ID_prop DESC');
        return $stmt->fetchAll();
    }

    public function findLatest(int $limit = 10): array
    {
        $stmt = $this->db->prepare('SELECT * FROM propositions ORDER BY ID_prop DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}