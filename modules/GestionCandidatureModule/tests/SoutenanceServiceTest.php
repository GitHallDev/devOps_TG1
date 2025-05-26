<?php

namespace Modules\GestionCandidatureModule\Tests;

use PHPUnit\Framework\TestCase;
use Modules\GestionCandidatureModule\Service\SoutenanceService;
use Modules\GestionCandidatureModule\Repository\SoutenanceRepository;

class SoutenanceServiceTest extends TestCase
{
    private $soutenanceService;
    private $soutenanceRepository;

    protected function setUp(): void
    {
        $this->soutenanceRepository = $this->createMock(SoutenanceRepository::class);
        $this->soutenanceService = new SoutenanceService($this->soutenanceRepository);
    }

    public function testCreateSoutenance()
    {
        $soutenanceData = [
            'id_stage' => 1,
            'date_soutenance' => '2024-02-15 14:00:00',
            'salle' => 'Salle A101',
            'jury' => 'Dr. Smith, Prof. Johnson',
            'statut' => 'planifiee'
        ];

        $this->soutenanceRepository->expects($this->once())
            ->method('createSoutenance')
            ->with($soutenanceData)
            ->willReturn(1);

        $result = $this->soutenanceService->createSoutenance($soutenanceData);

        $this->assertEquals(1, $result);
    }

    public function testGetAllSoutenances()
    {
        $expectedSoutenances = [
            [
                'id' => 1,
                'id_stage' => 1,
                'date_soutenance' => '2024-02-15 14:00:00',
                'salle' => 'Salle A101',
                'statut' => 'planifiee'
            ],
            [
                'id' => 2,
                'id_stage' => 2,
                'date_soutenance' => '2024-02-16 10:00:00',
                'salle' => 'Salle B202',
                'statut' => 'terminee'
            ]
        ];

        $this->soutenanceRepository->expects($this->once())
            ->method('getAllSoutenances')
            ->willReturn($expectedSoutenances);

        $result = $this->soutenanceService->getAllSoutenances();

        $this->assertEquals($expectedSoutenances, $result);
    }

    public function testGetSoutenanceById()
    {
        $soutenanceId = 1;
        $expectedSoutenance = [
            'id' => $soutenanceId,
            'id_stage' => 1,
            'date_soutenance' => '2024-02-15 14:00:00',
            'salle' => 'Salle A101',
            'jury' => 'Dr. Smith, Prof. Johnson',
            'statut' => 'planifiee',
            'etudiant' => [
                'nom' => 'Doe',
                'prenom' => 'John'
            ],
            'stage' => [
                'sujet' => 'Développement d\'une application web',
                'entreprise' => 'Tech Solutions'
            ]
        ];

        $this->soutenanceRepository->expects($this->once())
            ->method('getSoutenanceById')
            ->with($soutenanceId)
            ->willReturn($expectedSoutenance);

        $result = $this->soutenanceService->getSoutenanceById($soutenanceId);

        $this->assertEquals($expectedSoutenance, $result);
    }

    public function testUpdateSoutenance()
    {
        $soutenanceId = 1;
        $updateData = [
            'date_soutenance' => '2024-02-16 15:00:00',
            'salle' => 'Salle C303',
            'jury' => 'Dr. Brown, Prof. Davis'
        ];

        $this->soutenanceRepository->expects($this->once())
            ->method('updateSoutenance')
            ->with($soutenanceId, $updateData)
            ->willReturn(true);

        $result = $this->soutenanceService->updateSoutenance($soutenanceId, $updateData);

        $this->assertTrue($result);
    }

    public function testUpdateSoutenanceStatus()
    {
        $soutenanceId = 1;
        $newStatus = 'terminee';

        $this->soutenanceRepository->expects($this->once())
            ->method('updateStatus')
            ->with($soutenanceId, $newStatus)
            ->willReturn(true);

        $result = $this->soutenanceService->updateStatus($soutenanceId, $newStatus);

        $this->assertTrue($result);
    }

    public function testDeleteSoutenance()
    {
        $soutenanceId = 1;

        $this->soutenanceRepository->expects($this->once())
            ->method('deleteSoutenance')
            ->with($soutenanceId)
            ->willReturn(true);

        $result = $this->soutenanceService->deleteSoutenance($soutenanceId);

        $this->assertTrue($result);
    }

    public function testGetSoutenancesByPeriod()
    {
        $startDate = '2024-02-01';
        $endDate = '2024-02-28';
        $expectedSoutenances = [
            [
                'id' => 1,
                'date_soutenance' => '2024-02-15 14:00:00',
                'salle' => 'Salle A101'
            ],
            [
                'id' => 2,
                'date_soutenance' => '2024-02-16 10:00:00',
                'salle' => 'Salle B202'
            ]
        ];

        $this->soutenanceRepository->expects($this->once())
            ->method('getSoutenancesByPeriod')
            ->with($startDate, $endDate)
            ->willReturn($expectedSoutenances);

        $result = $this->soutenanceService->getSoutenancesByPeriod($startDate, $endDate);

        $this->assertEquals($expectedSoutenances, $result);
    }
}