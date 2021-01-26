<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FournisseurRepository::class)
 */
class Fournisseur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codefour;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\OneToMany(targetEntity=Contacts::class, mappedBy="fournisseur")
     */
    private $contacts;

    /**
     * @ORM\ManyToMany(targetEntity=Famille::class, mappedBy="fournisseur")
     */
    private $familles;

    /**
     * @ORM\ManyToMany(targetEntity=Nuance::class, mappedBy="fournisseur")
     */
    private $nuances;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->familles = new ArrayCollection();
        $this->nuances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodefour(): ?int
    {
        return $this->codefour;
    }

    public function setCodefour(int $codefour): self
    {
        $this->codefour = $codefour;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection|Contacts[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contacts $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setFournisseur($this);
        }

        return $this;
    }

    public function removeContact(Contacts $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getFournisseur() === $this) {
                $contact->setFournisseur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|famille[]
     */
    public function getFamilles(): Collection
    {
        return $this->familles;
    }

    public function addFamille(famille $famille): self
    {
        if (!$this->familles->contains($famille)) {
            $this->familles[] = $famille;
            $famille->addFournisseur($this);
        }

        return $this;
    }

    public function removeFamille(famille $famille): self
    {
        if ($this->familles->removeElement($famille)) {
            $famille->removeFournisseur($this);
        }

        return $this;
    }

    /**
     * @return Collection|nuance[]
     */
    public function getNuances(): Collection
    {
        return $this->nuances;
    }

    public function addNuance(nuance $nuance): self
    {
        if (!$this->nuances->contains($nuance)) {
            $this->nuances[] = $nuance;
            $nuance->addFournisseur($this);
        }

        return $this;
    }

    public function removeNuance(nuance $nuance): self
    {
        if ($this->nuances->removeElement($nuance)) {
            $nuance->removeFournisseur($this);
        }

        return $this;
    }
}
