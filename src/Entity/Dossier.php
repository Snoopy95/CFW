<?php

namespace App\Entity;

use App\Repository\DossierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

    /**
     * @ORM\Entity(repositoryClass=DossierRepository::class)
     * @UniqueEntity(
     * fields = {"numdossier"},
     * message ="Numero déja utilisé"
     * )
     */

class Dossier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(message="Superieur à zero")
     */
    private $numdossier;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $refpiece;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $desigpiece;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateupdate;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(
     * mimeTypes={ "application/pdf" },
     * mimeTypesMessage ="Uniquement un PDF"
     * )
     */
    private $plan;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File()
     */
    private $step;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $ind;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumdossier(): ?int
    {
        return $this->numdossier;
    }

    public function setNumdossier(int $numdossier): self
    {
        $this->numdossier = $numdossier;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getRefpiece(): ?string
    {
        return $this->refpiece;
    }

    public function setRefpiece(string $refpiece): self
    {
        $this->refpiece = $refpiece;

        return $this;
    }

    public function getDesigpiece(): ?string
    {
        return $this->desigpiece;
    }

    public function setDesigpiece(string $desigpiece): self
    {
        $this->desigpiece = $desigpiece;

        return $this;
    }

    public function getDatecreat(): ?\DateTimeInterface
    {
        return $this->datecreat;
    }

    public function setDatecreat(\DateTimeInterface $datecreat): self
    {
        $this->datecreat = $datecreat;

        return $this;
    }

    public function getDateupdate(): ?\DateTimeInterface
    {
        return $this->dateupdate;
    }

    public function setDateupdate(?\DateTimeInterface $dateupdate): self
    {
        $this->dateupdate = $dateupdate;

        return $this;
    }

    public function getPlan()
    {
        return $this->plan;
    }

    public function setPlan($plan)
    {
        $this->plan = $plan;

        return $this;
    }

    public function getStep()
    {
        return $this->step;
    }

    public function setStep($step)
    {
        $this->step = $step;

        return $this;
    }

    public function getInd(): ?string
    {
        return $this->ind;
    }

    public function setInd(?string $ind): self
    {
        $this->ind = $ind;

        return $this;
    }
}
