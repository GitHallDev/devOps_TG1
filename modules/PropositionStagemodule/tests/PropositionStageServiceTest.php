<?php

namespace Modules\PropositionStagemodule\Tests;

use PHPUnit\Framework\TestCase;
use Modules\PropositionStagemodule\Service\PropositionstageService;
use Modules\PropositionStagemodule\Repository\PropositionStageRepository;

class PropositionStageServiceTest extends TestCase
{
    private $propositionService;
    private $propositionRepository;

    protected function setUp(): void
    {
        $this->propositionRepository = $this->createMock(PropositionStageRepository::class);
        $this->propositionService = new PropositionstageService($this->propositionRepository);
    }

    public function testCreateProposition()
    {
        $propositionData = [
            'titre' => 'Stage développeur Full Stack',
            'description' => 'Stage de 6 mois en développement web',
            'entreprise' => 'Tech Solutions',
            'date_debut' => '2024-03-01',
            'date_fin' => '2024-08-31',
            'competences_requises' => 'PHP, JavaScript, MySQL',
            'remuneration' => 1000,
            'id_createur' => 1,
            'statut' => 'en_attente'
        ];

        $this->propositionRepository->expects($this->once())
            ->method('createProposition')
            ->with($propositionData)
            ->willReturn(1);

        $result = $this->propositionService->createProposition($propositionData);

        $this->assertEquals(1, $result);
    }

    public function testGetAllPropositions()
    {
        $expectedPropositions = [
            [
                'id' => 1,
                'titre' => 'Stage développeur Full Stack',
                'entreprise' => 'Tech Solutions',
                'statut' => 'en_attente'
            ],
            [
                'id' => 2,
                'titre' => 'Stage Data Analyst',
                'entreprise' => 'Data Corp',
                'statut' => 'validee'
            ]
        ];

        $this->propositionRepository->expects($this->once())
            ->method('getAllPropositions')
            ->willReturn($expectedPropositions);

        $result = $this->propositionService->getAllPropositions();

        $this->assertEquals($expectedPropositions, $result);
    }

    public function testUpdateProposition()
    {
        $propositionId = 1;
        $updateData = [
            'titre' => 'Stage développeur Full Stack (mis à jour)',
            'description' => 'Description mise à jour',
            'competences_requises' => 'PHP, JavaScript, MySQL, React'
        ];

        $this->propositionRepository->expects($this->once())
            ->method('updateProposition')
            ->with($propositionId, $updateData)
            ->willReturn(true);

        $result = $this->propositionService->updateProposition($propositionId, $updateData);

        $this->assertTrue($result);
    }

    public function testDeleteProposition()
    {
        $propositionId = 1;

        $this->propositionRepository->expects($this->once())
            ->method('deleteProposition')
            ->with($propositionId)
            ->willReturn(true);

        $result = $this->propositionService->deleteProposition($propositionId);

        $this->assertTrue($result);
    }

    public function testUpdateStatut()
    {
        $propositionId = 1;
        $nouveauStatut = 'validee';

        $this->propositionRepository->expects($this->once())
            ->method('updateStatut')
            ->with($propositionId, $nouveauStatut)
            ->willReturn(true);

        $result = $this->propositionService->updateStatut($propositionId, $nouveauStatut);

        $this->assertTrue($result);
    }

    public function testGetPropositionById()
    {
        $propositionId = 1;
        $expectedProposition = [
            'id' => $propositionId,
            'titre' => 'Stage développeur Full Stack',
            'description' => 'Stage de 6 mois en développement web',
            'entreprise' => 'Tech Solutions',
            'date_debut' => '2024-03-01',
            'date_fin' => '2024-08-31',
            'competences_requises' => 'PHP, JavaScript, MySQL',
            'remuneration' => 1000,
            'statut' => 'en_attente'
        ];

        $this->propositionRepository->expects($this->once())
            ->method('getPropositionById')
            ->with($propositionId)
            ->willReturn($expectedProposition);

        $result = $this->propositionService->getPropositionById($propositionId);

        $this->assertEquals($expectedProposition, $result);
    }

    public function testGetPropositionsByStatut()
    {
        $statut = 'validee';
        $expectedPropositions = [
            [
                'id' => 1,
                'titre' => 'Stage développeur Full Stack',
                'entreprise' => 'Tech Solutions',
                'statut' => 'validee'
            ],
            [
                'id' => 2,
                'titre' => 'Stage Data Analyst',
                'entreprise' => 'Data Corp',
                'statut' => 'validee'
            ]
        ];

        $this->propositionRepository->expects($this->once())
            ->method('getPropositionsByStatut')
            ->with($statut)
            ->willReturn($expectedPropositions);

        $result = $this->propositionService->getPropositionsByStatut($statut);

        $this->assertEquals($expectedPropositions, $result);
    }
}