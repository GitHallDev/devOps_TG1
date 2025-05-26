<?php
// src/Service/SoutenanceService.php
namespace Modules\GestionCandidatureModule\Service;

use Modules\GestionCandidatureModule\Repository\SoutenanceRepository;
use Modules\GestionCandidatureModule\Modele\Soutenance;

class SoutenanceService
{
    private SoutenanceRepository $repo;

    /**
     * Constructeur de la classe
     */
    public function __construct(SoutenanceRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Récupère toutes les soutenances
     * 
     * @return array
     */
    public function getAllSoutenances(): array
    {
        return $this->repo->getAllSoutenances();
    }

    /**
     * Récupère une soutenance par son ID
     * 
     * @param int $id L'ID de la soutenance
     * @return Soutenance|null
     */
    public function getSoutenanceById(int $id): ?Soutenance
    {
        return $this->repo->getSoutenanceById($id);
    }

    /**
     * Met à jour le statut d'une soutenance
     * 
     * @param int $id L'ID de la soutenance
     * @param string $resultat Le nouveau statut
     * @return bool
     */
    public function updateSoutenanceStatus(int $id, string $resultat): bool
    {
        return $this->repo->updateSoutenanceStatus($id, $resultat);
    }

    /**
     * Crée une nouvelle soutenance
     * 
     * @param array $data Les données de la soutenance
     * @return int|false L'ID de la nouvelle soutenance ou false en cas d'échec
     */
    public function createSoutenance(array $data)
    {
        return $this->repo->createSoutenance($data);
    }

    /**
     * Met à jour une soutenance existante
     * 
     * @param int $id L'ID de la soutenance
     * @param array $data Les nouvelles données
     * @return bool
     */
    public function updateSoutenance(int $id, array $data): bool
    {
        return $this->repo->updateSoutenance($id, $data);
    }

    /**
     * Supprime une soutenance
     * 
     * @param int $id L'ID de la soutenance
     * @return bool
     */
    public function deleteSoutenance(int $id): bool
    {
        return $this->repo->deleteSoutenance($id);
    }
}