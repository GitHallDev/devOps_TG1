<?php
// src/Modele/Candidature.php
namespace Modules\GestionCandidatureModule\Modele;
    // Define the enum outside the class


class Candidature
{
    private  $cv;
    private  $cover_letter;
    private string $statuts;

    public function __construct($cv,$cover_letter,string $statuts){
        $this->cv=$cv;
        $this->cover_letter=$cover_letter;
        $this->statuts=$statuts;
    }

    public function toJSON(){
        return json_encode([
            'cv'=>$this->cv,
            'cover_letter'=>$this->cover_letter,
            'string'=>$this->statuts
        ]);
    }
    public function fromJSON($json){
        $data = json_decode($json, true);
        $this->cv = $data['cv'];
        $this->cover_letter = $data['cover_letter'];
        $this->statuts = string::from($data['statuts']);
    }

    public function getCv(){
        return $this->cv;
    }
    public function getCoverLetter(){
        return $this->cover_letter;
    }
    public function getStatuts(){
        return $this->statuts;
    }
    public function setCv($cv){
        $this->cv=$cv;
    }
    public function setCoverLetter($cover_letter){
        $this->cover_letter=$cover_letter;
    }
    public function setStatuts(string $statuts){
        $this->string=$statuts;
    }
    public function __toString(){
        return $this->toJSON();
    }
    public function __debugInfo(){
        return [
            'cv'=>$this->cv,
            'cover_letter'=>$this->cover_letter,
            'statuts'=>$this->statuts
        ];
    }
    public function __serialize(): array
    {
        return [
            'cv' => $this->cv,
            'cover_letter' => $this->cover_letter,
            'statuts' => $this->statuts
        ];
    }
    public function __unserialize(array $data): void
    {
        $this->cv = $data['cv'];
        $this->cover_letter = $data['cover_letter'];
        $this->statuts = string::from($data['statuts']);
    }
}