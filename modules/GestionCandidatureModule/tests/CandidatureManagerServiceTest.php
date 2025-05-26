<?php

namespace Modules\GestionCandidatureModule\Tests;

use PHPUnit\Framework\TestCase;
use Modules\GestionCandidatureModule\Service\CandidatureManagerService;
use Modules\GestionCandidatureModule\Repository\CandidatureManagerRepository;

class CandidatureManagerServiceTest extends TestCase
{
    private $candidatureService;
    private $candidatureRepository;

    protected function setUp(): void
    {
        $this->candidatureRepository = $this->createMock(CandidatureManagerRepository::class);
        $this->candidatureService = new CandidatureManagerService($this->candidatureRepository);
    }

    public function testCreateCandidature()
    {
        $candidatureData = [
            'id_etudiant' => 1,
            'id_proposition' => 2,
            'cv_path' => '/uploads/cv.pdf',
            'lettre_motivation_path' => '/uploads/lm.pdf',
            'date_candidature' => '2024-01-20',
            'statut' => 'en_attente'
        ];

        $this->candidatureRepository->expects($this->once())
            ->method('createCandidature')
            ->with($candidatureData)
            ->willReturn(true);

        $result = $this->candidatureService->createCandidature($candidatureData);

        $this->assertTrue($result);
    }

    public function testGetCandidaturesByEtudiant()
    {
        $etudiantId = 1;
        $expectedCandidatures = [
            [
                'id' => 1,
                'id_etudiant' => $etudiantId,
                'id_proposition' => 2,
                'statut' => 'en_attente'
            ],
            [
                'id' => 2,
                'id_etudiant' => $etudiantId,
                'id_proposition' => 3,
                'statut' => 'acceptee'
            ]
        ];

        $this->candidatureRepository->expects($this->once())
            ->method('getCandidaturesByEtudiant')
            ->with($etudiantId)
            ->willReturn($expectedCandidatures);

        $result = $this->candidatureService->getCandidaturesByEtudiant($etudiantId);

        $this->assertEquals($expectedCandidatures, $result);
    }

    public function testUpdateCandidatureStatus()
    {
        $candidatureId = 1;
        $newStatus = 'acceptee';

        $this->candidatureRepository->expects($this->once())
            ->method('updateCandidatureStatus')
            ->with($candidatureId, $newStatus)
            ->willReturn(true);

        $result = $this->candidatureService->updateCandidatureStatus($candidatureId, $newStatus);

        $this->assertTrue($result);
    }

    public function testDeleteCandidature()
    {
        $candidatureId = 1;

        $this->candidatureRepository->expects($this->once())
            ->method('deleteCandidature')
            ->with($candidatureId)
            ->willReturn(true);

        $result = $this->candidatureService->deleteCandidature($candidatureId);

        $this->assertTrue($result);
    }

    public function testGetCandidatureDetails()
    {
        $candidatureId = 1;
        $expectedDetails = [
            'id' => $candidatureId,
            'id_etudiant' => 1,
            'id_proposition' => 2,
            'cv_path' => '/uploads/cv.pdf',
            'lettre_motivation_path' => '/uploads/lm.pdf',
            'date_candidature' => '2024-01-20',
            'statut' => 'en_attente',
            'etudiant' => [
                'nom' => 'Doe',
                'prenom' => 'John'
            ],
            'proposition' => [
                'titre' => 'Stage développeur PHP',
                'entreprise' => 'Tech Corp'
            ]
        ];

        $this->candidatureRepository->expects($this->once())
            ->method('getCandidatureDetails')
            ->with($candidatureId)
            ->willReturn($expectedDetails);

        $result = $this->candidatureService->getCandidatureDetails($candidatureId);

        $this->assertEquals($expectedDetails, $result);
    }
}