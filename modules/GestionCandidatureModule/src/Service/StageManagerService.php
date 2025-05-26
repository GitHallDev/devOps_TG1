<?php
// src/Service/StageManagerService.php
namespace Modules\GestionCandidatureModule\Service;
use Modules\GestionCandidatureModule\Repository\StageManagerRepository;
use \DateTime;

/**
 * Classe de service pour la gestion des stages effectifs.
 */
class StageManagerService
{
    private StageManagerRepository $repo;

    /**
     * Constructeur de la classe
     */
    public function  __construct(StageManagerRepository $repo){
        $this->repo =$repo;
    }

    /**
     * Crée un stage effectif pour un utilisateur donné.
     *
     * @param int $id_cand L'identifiant de la candidature.
     * @param int $id_user L'identifiant de l'utilisateur.
     * @param DateTime $start_date La date de début du stage.
     * @param DateTime $end_date La date de fin du stage.
     * @param string $sujet_effectif Le sujet effectif du stage.
     * @param int $remuneration_effective La rémunération effective du stage.
     * @param string $statuts Le statut du stage.
     * @param string $encadreur L'encadreur du stage.
     */
    public function create_stage_effectif(?int $id_cand, int $id_user, DateTime $start_date, DateTime $end_date, string $sujet_effectif, int $remuneration_effective, string $statuts, string $encadreur):bool{
        try {
             $this->repo->create_stage_effectif($id_cand, $id_user, $start_date, $end_date, $sujet_effectif, $remuneration_effective, $statuts, $encadreur);
             return true;
        } catch (\Throwable $th) {
            echo "Echec de la création d'un stage effectif:".$th;
            return false;
        }
    }

    /**
     * Gets all effective internships.
     *
     * @return array Array of all effective internships
     */
    public function get_all_stages_effectifs(): array {
        try {
            return $this->repo->getAllStage();
        } catch (\Throwable $th) {
            echo "Failed to retrieve effective internships: " . $th;
            return [];
        }
    }

    public function get_all_stages_effectifs_en_cours(): array {
        try {
            return $this->repo->getAllStageEnCours();
        } catch (\Throwable $th) {
            echo "Failed to retrieve effective internships: " . $th;
            return [];
        }
    }

    /**
     * Gets an effective internship by its ID.
     *
     * @param int $id The internship ID
     * @return array|null The internship data or null if not found
     */
    public function get_stage_effectif_by_id(int $id): ?array {
        try {
            return $this->repo->get_stage_effectif_by_id($id);
        } catch (\Throwable $th) {
            echo "Failed to retrieve effective internship: " . $th;
            return null;
        }
    }

    /**
     * Gets all effective internships for a specific user.
     *
     * @param int $id_user The user ID
     * @return array Array of user's effective internships
     */
    public function get_stages_effectifs_by_user(int $id_user): array {
        try {
            return $this->repo->get_stages_effectifs_by_user($id_user);
        } catch (\Throwable $th) {
            echo "Failed to retrieve user's effective internships: " . $th;
            return [];
        }
    }

    /**
     * Updates an effective internship.
     *
     * @param int $id The internship ID
     * @param int|null $id_cand The application ID
     * @param int $id_user The user ID
     * @param DateTime $start_date Start date
     * @param DateTime $end_date End date
     * @param string $sujet_effectif Actual internship subject
     * @param int $remuneration_effective Actual compensation
     * @param string $statuts Status
     * @param string $encadreur Supervisor
     */
    public function update_stage_effectif(int $id, ?int $id_cand, int $id_user, DateTime $start_date, DateTime $end_date, string $sujet_effectif, int $remuneration_effective, string $statuts, string $encadreur): bool {
        try {
            error_log("Service : Mise à jour du stage ID : " . $id);
            $result = $this->repo->update_stage_effectif($id, $id_cand, $id_user, $start_date, $end_date, $sujet_effectif, $remuneration_effective, $statuts, $encadreur);
            error_log("Service : Résultat de la mise à jour : " . ($result ? "succès" : "échec"));
            return $result;
        } catch (\Throwable $th) {
            error_log("Service : Erreur lors de la mise à jour du stage : " . $th->getMessage());
            return false;
        }
    }

    /**
     * Deletes an effective internship.
     *
     * @param int $id The internship ID
     */
    public function delete_stage_effectif(int $id): void {
        try {
            $this->repo->delete_stage_effectif($id);
        } catch (\Throwable $th) {
            echo "Failed to delete effective internship: " . $th;
        }
    }
}