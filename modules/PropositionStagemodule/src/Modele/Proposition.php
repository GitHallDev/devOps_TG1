<?php
// src/Modele/Proposition.php
namespace Modules\PropositionStagemodule\Modele;

class Proposition
{
    private string $sujet;
    private string $Duree;
    private string $remuneration;
    private string $statuts;
    private string $create_by;

    public function __construct(string $sujet, string $Duree, string $remuneration, string $statuts, string $create_by)
    {
        $this->sujet = $sujet;
        $this->Duree = $Duree;
        $this->remuneration = $remuneration;
        $this->statuts = $statuts;
        $this->create_by = $create_by;
    }

    public function toJSON(): string
    {
        return json_encode([
            'sujet' => $this->sujet,
            'Duree' => $this->Duree,
            'remuneration' => $this->remuneration,
            'statuts' => $this->statuts,
            'create_by' => $this->create_by,
        ]);
    }

    public function fromJSON(string $json): void
    {
        $data = json_decode($json, true);
        $this->sujet = $data['sujet'];
        $this->Duree = $data['Duree'];
        $this->remuneration = $data['remuneration'];
        $this->statuts = $data['statuts'];
        $this->create_by = $data['create_by'];
    }

    // Getters
    public function getSujet(): string { return $this->sujet; }
    public function getDuree(): string { return $this->Duree; }
    public function getRemuneration(): string { return $this->remuneration; }
    public function getStatuts(): string { return $this->statuts; }
    public function getCreateBy(): string { return $this->create_by; }

    // Setters
    public function setSujet(string $sujet): void { $this->sujet = $sujet; }
    public function setDuree(string $Duree): void { $this->Duree = $Duree; }
    public function setRemuneration(string $remuneration): void { $this->remuneration = $remuneration; }
    public function setStatuts(string $statuts): void { $this->statuts = $statuts; }
    public function setCreateBy(string $create_by): void { $this->create_by = $create_by; }

    // Magic Methods
    public function __toString(): string
    {
        return $this->toJSON();
    }

    public function __debugInfo(): array
    {
        return [
            'sujet' => $this->sujet,
            'Duree' => $this->Duree,
            'remuneration' => $this->remuneration,
            'statuts' => $this->statuts,
            'create_by' => $this->create_by,
        ];
    }

    public function __serialize(): array
    {
        return [
            'sujet' => $this->sujet,
            'Duree' => $this->Duree,
            'remuneration' => $this->remuneration,
            'statuts' => $this->statuts,
            'create_by' => $this->create_by,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->sujet = $data['sujet'];
        $this->Duree = $data['Duree'];
        $this->remuneration = $data['remuneration'];
        $this->statuts = $data['statuts'];
        $this->create_by = $data['create_by'];
    }
}
