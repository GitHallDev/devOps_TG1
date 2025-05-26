<?php
// src/modules/PropositionStagemodule/Service/PropositionStageService.php
namespace Modules\PropositionStagemodule\Service;

use Modules\PropositionStagemodule\Repository\PropositionStageRepository;
use Modules\PropositionStagemodule\Modele\Proposition;
class PropositionStageService
{
    private PropositionStageRepository $repo;

    public function __construct(PropositionStageRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Récupère toutes les propositions disponibles.
     *
     * @return array
     */
    public function getAllProposition(): array
    {
        return $this->repo->findAll();
    }

    /**
     * Récupère toutes les propositions disponibles.
     *
     * @return array
     */
    public function getAllPropositionAccepted(): array
    {
        return $this->repo->findAllAccepted();
    }

    public function getLatestPropositions(int $limit = 10): array
    {
        return $this->repo->findLatest($limit);
    }

    // integration Salamata partie
    
    /**
     * Enregistre une nouvelle proposition de stage.
     *
     * @param Proposition $proposition
     * @return bool
     */
    public function enregistrerProposition(Proposition $proposition): bool {
        try {
            // echo"<pre>";
            // print_r($proposition);
            // echo"</pre>";
            $this->repo->saveProposition([
                'sujet' => $proposition->getSujet(),
                'Duree' => $proposition->getDuree(),
                'remuneration' => $proposition->getRemuneration(),
                'statuts' => $proposition->getStatuts(),
                'create_by' => $proposition->getCreateBy()
            ]);
            return true;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Échec de l\'enregistrement de la proposition : ' . $e->getMessage());
        }
    }

     /* Change le statut d'une proposition de stage.
     *
     * @param int $id L'ID de la proposition.
     * @param string $nouveauStatut Le nouveau statut à définir (ex: 'accepté', 'refusé').
     * @return bool Retourne true si succès, false sinon.
     */
    public function changerStatut(int $id, string $nouveauStatut): bool
    {
        try {
            $this->repo->updateStatutProposition($id, $nouveauStatut);
            return true;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erreur lors du changement de statut : ' . $e->getMessage());
        }
    }

    
    public function updateProposition(int $id, array $proposition): void
    {
        try {
            $this->repo->updateProposition($id, $proposition);
        } catch (\Throwable $e) {
            echo "Erreur lors de la mise à jour de la proposition : " . $e->getMessage();
        }
    }


    public function deleteProposition(int $id): bool
    {
        try {
            $this->repo->deleteProposition($id);
            return true;
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erreur lors de la suppression de la proposition : ' . $e->getMessage());
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
        return $this->repo->getPropositionById($id);
    }
}
