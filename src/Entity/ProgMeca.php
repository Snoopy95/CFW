<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ProgMecaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgMecaRepository::class)
 */
class ProgMeca
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typemachine;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numprog;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ind;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(
     * mimeTypes={ "application/pdf" },
     * mimeTypesMessage ="Uniquement un PDF"
     * )
     */
    private $plan;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File()
     */
    private $step;

    /**
     * @ORM\Column(type="integer")
     */
    private $compteur;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $refpiece;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $desigpiece;

    private $retourplan;

    private $retourstep;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypemachine(): ?string
    {
        return $this->typemachine;
    }

    public function setTypemachine(string $typemachine): self
    {
        $this->typemachine = $typemachine;

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

    public function getNumprog(): ?string
    {
        return $this->numprog;
    }

    public function setNumprog(string $numprog): self
    {
        $this->numprog = $numprog;

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

    public function getInd(): ?string
    {
        return $this->ind;
    }

    public function setInd(?string $ind): self
    {
        $this->ind = $ind;

        return $this;
    }

    public function getPlan(): ?string
    {
        return $this->plan;
    }

    public function setPlan(?string $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getStep(): ?string
    {
        return $this->step;
    }

    public function setStep(?string $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getCompteur(): ?int
    {
        return $this->compteur;
    }

    public function setCompteur(int $compteur): self
    {
        $this->compteur = $compteur;

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
    public function getRetourplan(): ?string
    {
        return $this->retourplan;
    }

    public function setRetourplan(string $retourplan): self
    {
        $this->retourplan = $retourplan;

        return $this;
    }
    public function getRetourstep(): ?string
    {
        return $this->retourstep;
    }

    public function setRetourstep(string $retourstep): self
    {
        $this->retourstep = $retourstep;

        return $this;
    }
}
