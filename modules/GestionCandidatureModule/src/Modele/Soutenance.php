<?php
// src/Modele/Soutenance.php
namespace Modules\GestionCandidatureModule\Modele;

class Soutenance
{
    private int $id;
    private string $date;
    private array $jury;
    private string $resultat;
    private int $id_stage;

    public function __construct(int $id, string $date, array $jury, string $resultat, int $id_stage)
    {
        $this->id = $id;
        $this->date = $date;
        $this->jury = $jury;
        $this->resultat = $resultat;
        $this->id_stage = $id_stage;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getJury(): array
    {
        return $this->jury;
    }

    public function getResultat(): string
    {
        return $this->resultat;
    }

    public function setResultat(string $resultat): void
    {
        $this->resultat = $resultat;
    }

    public function getIdStage(): int
    {
        return $this->id_stage;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'jury' => $this->jury,
            'resultat' => $this->resultat,
            'id_stage' => $this->id_stage
        ];
    }
}