<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\ObjectType;

class Mail
{
    private $famille;
    
    private $nuance;

    private $debits;

    private $fournisseurs;

    public function __construct()
    {
        $this->debits = new ArrayCollection();
        $this->fournisseurs = new ArrayCollection();
    }

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille)
    {
        $this->famille = $famille;
    }

    public function getNuance(): ?Nuance
    {
        return $this->nuance;
    }

    public function setNuance(?Nuance $nuance)
    {
        $this->nuance = $nuance;
    }

    /**
     * @return Collection|Debits[]
     */
    public function getDebits(): Collection
    {
        return $this->debits;
    }

    public function setDebits($debits)
    {
        $this->debits[]= $debits;
    }

    /**
     * @return Collection|Fournisseurs[]
     */
    public function getFournisseurs(): Collection
    {
        return $this->fournisseurs;
    }

    public function setFournisseurs($fournisseurs)
    {
        $this->fournisseurs[] = $fournisseurs;
    }
}
