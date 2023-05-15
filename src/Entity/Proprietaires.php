<?php

namespace App\Entity;

use App\Repository\ProprietairesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProprietairesRepository::class)]
class Proprietaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $tel = null;

    #[ORM\OneToMany(mappedBy: 'proprietaires', targetEntity: Biens::class)]
    private Collection $bien;

    #[ORM\OneToMany(mappedBy: 'proprietaires', targetEntity: Abonnements::class)]
    private Collection $abonnement;

    #[ORM\ManyToOne(inversedBy: 'proprio')]
    private ?Utilisateur $utilisateur = null;

    public function __construct()
    {
        $this->bien = new ArrayCollection();
        $this->abonnement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * @return Collection<int, Biens>
     */
    public function getBien(): Collection
    {
        return $this->bien;
    }

    public function addBien(Biens $bien): self
    {
        if (!$this->bien->contains($bien)) {
            $this->bien->add($bien);
            $bien->setProprietaires($this);
        }

        return $this;
    }

    public function removeBien(Biens $bien): self
    {
        if ($this->bien->removeElement($bien)) {
            // set the owning side to null (unless already changed)
            if ($bien->getProprietaires() === $this) {
                $bien->setProprietaires(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Abonnements>
     */
    public function getAbonnement(): Collection
    {
        return $this->abonnement;
    }

    public function addAbonnement(Abonnements $abonnement): self
    {
        if (!$this->abonnement->contains($abonnement)) {
            $this->abonnement->add($abonnement);
            $abonnement->setProprietaires($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnements $abonnement): self
    {
        if ($this->abonnement->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getProprietaires() === $this) {
                $abonnement->setProprietaires(null);
            }
        }

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
