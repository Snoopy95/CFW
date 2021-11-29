<?php

namespace App\Entity;

use Doctrine\DBAL\Types\ObjectType;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class Mails
{
    private $fournisseur;

    private $adressmails;

    private $debits;

    public function __construct()
    {
        $this->debits = new ArrayCollection();
        $this->adressmails = new ArrayCollection();
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur)
    {
        $this->fournisseur = $fournisseur;
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
     * @return Collection|Adressmails[]
     */
    public function getAdressmails(): Collection
    {
        return $this->adressmails;
    }

    public function setAdresmails($adressmails)
    {
        $this->adressmails[] = $adressmails;
    }
}
