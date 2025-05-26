<?php
// src/Repository/SoutenanceRepository.php
namespace Modules\GestionCandidatureModule\Repository;

use App\MailService;
use PDO;
use Modules\GestionCandidatureModule\Modele\Soutenance;

class SoutenanceRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $c = new \App\Container();
        $this->pdo = $c->get(\PDO::class);
    }

    /**
     * Récupère toutes les soutenances
     * 
     * @return array
     */
    public function getAllSoutenances(): array
    {
        $stmt = $this->pdo->prepare('SELECT s.ID_sout, s.Date, s.resultat, s.ID_stage, st.Sujet_effectif 
                                     FROM t1_soutenances s
                                     JOIN t1_stages st ON s.ID_stage = st.ID_stage');
        $stmt->execute();
        $soutenances = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Formatage pour FullCalendar
            $soutenances[] = [
                'id' => $row['ID_sout'],
                'title' => $row['Sujet_effectif'],
                'start' => (new \DateTime($row['Date']))->format('Y-m-d\\TH:i:s'), // Format ISO8601
                'allDay' => false,
                'className' => 'soutenance-' . $row['resultat'] // Pour le style CSS
            ];
        }

        return $soutenances;
    }

    /**
     * Récupère une soutenance par son ID
     * 
     * @param int $id L'ID de la soutenance
     * @return Soutenance|null
     */
    public function getSoutenanceById(int $id): ?Soutenance
    {

        $stmt = $this->pdo->prepare('SELECT s.ID_sout, s.Date, s.resultat, s.ID_stage, s.ID_jury, st.Sujet_effectif, u.nom, u.prenom 
                                     FROM t1_soutenances s
                                     JOIN t1_stages st ON s.ID_stage = st.ID_stage
                                     JOIN t1_users u ON st.id_user = u.id_user
                                     WHERE s.ID_sout = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        // Récupérer les membres du jury
        $stmtJury = $this->pdo->prepare('SELECT jury_1, jury_2, jury_3 
                                        FROM t1_jurys
                                        WHERE Id_jury = :id_jury');
        $stmtJury->execute([':id_jury' => $row['ID_jury']]);
        $juryData = $stmtJury->fetch(PDO::FETCH_ASSOC);

        $jury = [];
        if ($juryData) {
            $jury = [
            $juryData['jury_1'],
            $juryData['jury_2'],
            $juryData['jury_3']
                ];
        }

        // Créer l'objet soutenance avec les données récupérées
        return new Soutenance(
            $row['ID_sout'],
            $row['Date'],
            $jury,
            $row['resultat'],
            $row['ID_stage']
        );
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
        try {
            $stmt = $this->pdo->prepare('UPDATE t1_soutenances SET resultat = :resultat WHERE ID_sout = :id');
             $stmt->execute([
                ':id' => $id,
                ':resultat' => $resultat
            ]);
            if($stmt->rowCount()>0){
            $mailService = new MailService($this->pdo);
            $mailService->envoyerNotification('soutenances', $id, $resultat);
            return True;
            }
            return false;
        } catch (\Throwable $th) {
         throw new \RunTimeException("Error Processing Request: " . $th->getMessage(), 500);
            //throw $th;
        }
    }

    /**
     * Crée une nouvelle soutenance
     * 
     * @param array $data Les données de la soutenance
     * @return int|false L'ID de la nouvelle soutenance ou false en cas d'échec
     */
    public function createSoutenance(array $data): int|false
    {
        try {
            $this->pdo->beginTransaction();

            // Créer d'abord le jury
            $stmtJury = $this->pdo->prepare('INSERT INTO t1_jurys (jury_1, jury_2, jury_3) VALUES (:jury1, :jury2, :jury3)');
            $stmtJury->execute([
                ':jury1' => $data['jury'][0],
                ':jury2' => $data['jury'][1],
                ':jury3' => $data['jury'][2]
            ]);
            $juryId = $this->pdo->lastInsertId();

            // Créer ensuite la soutenance
            $stmt = $this->pdo->prepare('INSERT INTO t1_soutenances (Date, ID_jury, resultat, ID_stage) 
                                        VALUES (:date, :id_jury, :resultat, :id_stage)');
            $stmt->execute([
                ':date' => $data['date'],
                ':id_jury' => $juryId,
                ':resultat' => 'en_cours',
                ':id_stage' => $data['id_stage']
            ]);

            $soutenanceId = $this->pdo->lastInsertId();
            $this->pdo->commit();
            return $soutenanceId;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
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
        try {
            $this->pdo->beginTransaction();

            // Récupérer l'ID du jury actuel
            $stmt = $this->pdo->prepare('SELECT ID_jury FROM t1_soutenances WHERE ID_sout = :id');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }
            $juryId = $row['ID_jury'];

            // Mettre à jour le jury
            $stmtJury = $this->pdo->prepare('UPDATE t1_jurys 
                                            SET jury_1 = :jury1, jury_2 = :jury2, jury_3 = :jury3 
                                            WHERE Id_jury = :id_jury');
            $stmtJury->execute([
                ':jury1' => $data['jury'][0],
                ':jury2' => $data['jury'][1],
                ':jury3' => $data['jury'][2],
                ':id_jury' => $juryId
            ]);

            // Mettre à jour la soutenance
            $stmt = $this->pdo->prepare('UPDATE t1_soutenances 
                                        SET Date = :date, ID_stage = :id_stage 
                                        WHERE ID_sout = :id');
            $success = $stmt->execute([
                ':date' => $data['date'],
                ':id_stage' => $data['id_stage'],
                ':id' => $id
            ]);
            echo json_encode(['success' => True, 'data' => $data, 'id' => $id,'id_stage'=> $data['id_stage']]);
            $this->pdo->commit();
            return $success;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * Supprime une soutenance
     * 
     * @param int $id L'ID de la soutenance
     * @return bool
     */
    public function deleteSoutenance(int $id): bool
    {
        try {
            $this->pdo->beginTransaction();

            // Récupérer l'ID du jury
            $stmt = $this->pdo->prepare('SELECT ID_jury FROM t1_soutenances WHERE ID_sout = :id');
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return false;
            }
            $juryId = $row['ID_jury'];

            // Supprimer la soutenance
            $stmt = $this->pdo->prepare('DELETE FROM t1_soutenances WHERE ID_sout = :id');
            $stmt->execute([':id' => $id]);

            // Supprimer le jury associé
            $stmtJury = $this->pdo->prepare('DELETE FROM t1_jurys WHERE Id_jury = :id_jury');
            $stmtJury->execute([':id_jury' => $juryId]);

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}