<?php

namespace Src\Entity;

use App\Core\Abstract\AbstractEntity;

class Journal extends AbstractEntity
{
    private int $id ;
    private string $cni;
    private string $ipAdresse;
    private string $localisation;
    private string $dateRecherche;
    private Statut $statut;

    public function __construct(string $cni, string $ipAdresse, string $localisation,string $dateRecherche,Statut $statut)
    {
        $this->cni = $cni;
        $this->ipAdresse = $ipAdresse;
        $this->localisation = $localisation;
        $this->dateRecherche = $dateRecherche;
        $this->statut = $statut;
    }


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of cni
     */ 
    public function getCni()
    {
        return $this->cni;
    }

    /**
     * Set the value of cni
     *
     * @return  self
     */ 
    public function setCni($cni)
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * Get the value of ipAdresse
     */ 
    public function getIpAdresse()
    {
        return $this->ipAdresse;
    }

    /**
     * Set the value of ipAdresse
     *
     * @return  self
     */ 
    public function setIpAdresse($ipAdresse)
    {
        $this->ipAdresse = $ipAdresse;

        return $this;
    }

    /**
     * Get the value of localisation
     */ 
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set the value of localisation
     *
     * @return  self
     */ 
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get the value of dateRecherche
     */ 
    public function getDateRecherche()
    {
        return $this->dateRecherche;
    }

    /**
     * Set the value of dateRecherche
     *
     * @return  self
     */ 
    public function setDateRecherche($dateRecherche)
    {
        $this->dateRecherche = $dateRecherche;

        return $this;
    }

    /**
     * Get the value of statut
     */ 
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set the value of statut
     *
     * @return  self
     */ 
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

     public static function toObject(array $data): static
     {
         $statut = $data['statut'] instanceof Statut ? $data['statut'] : Statut::from($data['statut']);
         
         return new static(
             $data['cni'],
             $data['ip_adresse'],
             $data['localisation'],
             $data['date_recherche'],
             $statut
         );
     }

     public function toArray(): array
     {
         return [
             'id' => $this->id ?? null,
             'cni' => $this->cni,
             'ipAdresse' => $this->ipAdresse,
             'localisation' => $this->localisation,
             'dateRecherche' => $this->dateRecherche,
             'statut' => $this->statut->value
         ];
     }
}
