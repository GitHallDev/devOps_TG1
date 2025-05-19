<?php
// src/modules/PropositionStagemodule/Service/PropositionStageService.php
namespace Modules\PropositionStagemodule\Service;

use Modules\PropositionStagemodule\Repository\PropositionStageRepository;

class PropositionStageService
{
    private PropositionStageRepository $repo;

    public function __construct(PropositionStageRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAllProposition(): array
    {
        return $this->repo->findAll();
    }

    public function getLatestPropositions(int $limit = 10): array
    {
        return $this->repo->findLatest($limit);
    }
}
