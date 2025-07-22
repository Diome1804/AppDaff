<?php

namespace Src\Entity;

use App\Core\Abstract\AbstractEntity;

class Citoyen extends AbstractEntity
{
    private int $id;
    private string $nom;
    private string $prenom;
    private string $cni;
    private string $dateNaissance;
    private string $lieuNaissance;
    private string $photoIdentiteUrl;


    public function __construct(
        string $nom,
        string $prenom,
        string $cni,
        string $dateNaissance,
        string $lieuNaissance,
        string $photoIdentiteUrl
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->cni = $cni;
        $this->dateNaissance = $dateNaissance;
        $this->lieuNaissance = $lieuNaissance;
        $this->photoIdentiteUrl = $photoIdentiteUrl;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom(): string
    {
        return $this->nom;
    }
    public function getPrenom(): string
    {
        return $this->prenom;
    }
    public function getCni(): string
    {
        return $this->cni;
    }
    public function getDateNaissance(): string
    {
        return $this->dateNaissance;
    }
    public function getLieuNaissance(): string
    {
        return $this->lieuNaissance;
    }
    public function getPhotoIdentiteUrl(): string
    {
        return $this->photoIdentiteUrl;
    }



    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }
    public function setCni(string $cni): void
    {
        $this->cni = $cni;
    }
    public function setDateNaissance(string $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }
    public function setLieuNaissance(string $lieuNaissance): void
    {
        $this->lieuNaissance = $lieuNaissance;
    }
    public function setPhotoIdentiteUrl(string $url): void
    {
        $this->photoIdentiteUrl = $url;
    }

     public static function toObject(array $data): static
     {
         return new static(
             $data['nom'],
             $data['prenom'],
             $data['cni'],
             $data['date_naissance'],
             $data['lieu_naissance'],
             $data['photo_identite_url']
         );
     }

     public function toArray(): array
     {
         return [
             'id' => $this->id ?? null,
             'nom' => $this->nom,
             'prenom' => $this->prenom,
             'cni' => $this->cni,
             'dateNaissance' => $this->dateNaissance,
             'lieuNaissance' => $this->lieuNaissance,
             'photoIdentiteUrl' => $this->photoIdentiteUrl
         ];
     }
}
