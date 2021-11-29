<?php

namespace App\Entity;

use App\Repository\NuanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NuanceRepository::class)
 */
class Nuance
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codenuance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statutnuance;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreat;

    /**
     * @ORM\OneToMany(targetEntity=AppelOffre::class, mappedBy="nuance")
     */
    private $appelOffres;

    /**
     * @ORM\ManyToMany(targetEntity=Fournisseur::class, inversedBy="nuances")
     */
    private $fournisseur;

    public function __construct()
    {
        $this->appelOffres = new ArrayCollection();
        $this->fournisseur = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCodenuance(): ?string
    {
        return $this->codenuance;
    }

    public function setCodenuance(string $codenuance): self
    {
        $this->codenuance = $codenuance;

        return $this;
    }

    public function getStatutnuance(): ?string
    {
        return $this->statutnuance;
    }

    public function setStatutnuance(?string $statutnuance): self
    {
        $this->statutnuance = $statutnuance;

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

    /**
     * @return Collection|AppelOffre[]
     */
    public function getAppelOffres(): Collection
    {
        return $this->appelOffres;
    }

    public function addAppelOffre(AppelOffre $appelOffre): self
    {
        if (!$this->appelOffres->contains($appelOffre)) {
            $this->appelOffres[] = $appelOffre;
            $appelOffre->setNuance($this);
        }

        return $this;
    }

    public function removeAppelOffre(AppelOffre $appelOffre): self
    {
        if ($this->appelOffres->removeElement($appelOffre)) {
            // set the owning side to null (unless already changed)
            if ($appelOffre->getNuance() === $this) {
                $appelOffre->setNuance(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|fournisseur[]
     */
    public function getFournisseur(): Collection
    {
        return $this->fournisseur;
    }

    public function addFournisseur(fournisseur $fournisseur): self
    {
        if (!$this->fournisseur->contains($fournisseur)) {
            $this->fournisseur[] = $fournisseur;
        }

        return $this;
    }

    public function removeFournisseur(fournisseur $fournisseur): self
    {
        $this->fournisseur->removeElement($fournisseur);

        return $this;
    }
}
