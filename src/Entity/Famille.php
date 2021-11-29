<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FamilleRepository::class)
 */
class Famille
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
    private $nomfamille;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codefamille;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statutfamille;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreat;

    /**
     * @ORM\OneToMany(targetEntity=AppelOffre::class, mappedBy="famille")
     */
    private $appelOffres;

    /**
     * @ORM\ManyToMany(targetEntity=Fournisseur::class, inversedBy="familles")
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

    public function getNomfamille(): ?string
    {
        return $this->nomfamille;
    }

    public function setNomfamille(string $nomfamille): self
    {
        $this->nomfamille = $nomfamille;

        return $this;
    }

    public function getCodefamille(): ?string
    {
        return $this->codefamille;
    }

    public function setCodefamille(string $codefamille): self
    {
        $this->codefamille = $codefamille;

        return $this;
    }

    public function getStatutfamille(): ?string
    {
        return $this->statutfamille;
    }

    public function setStatutfamille(string $statutfamille): self
    {
        $this->statutfamille = $statutfamille;

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
            $appelOffre->setFamille($this);
        }

        return $this;
    }

    public function removeAppelOffre(AppelOffre $appelOffre): self
    {
        if ($this->appelOffres->removeElement($appelOffre)) {
            // set the owning side to null (unless already changed)
            if ($appelOffre->getFamille() === $this) {
                $appelOffre->setFamille(null);
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
