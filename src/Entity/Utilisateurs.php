<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'utilisateurs', targetEntity: Administrateur::class)]
    private Collection $admin;

    #[ORM\OneToMany(mappedBy: 'utilisateurs', targetEntity: Proprietaires::class)]
    private Collection $proprio;

    public function __construct()
    {
        $this->admin = new ArrayCollection();
        $this->proprio = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Administrateur>
     */
    public function getAdmin(): Collection
    {
        return $this->admin;
    }

    public function addAdmin(Administrateur $admin): self
    {
        if (!$this->admin->contains($admin)) {
            $this->admin->add($admin);
            $admin->setUtilisateurs($this);
        }

        return $this;
    }

    public function removeAdmin(Administrateur $admin): self
    {
        if ($this->admin->removeElement($admin)) {
            // set the owning side to null (unless already changed)
            if ($admin->getUtilisateurs() === $this) {
                $admin->setUtilisateurs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Proprietaires>
     */
    public function getProprio(): Collection
    {
        return $this->proprio;
    }

    public function addProprio(Proprietaires $proprio): self
    {
        if (!$this->proprio->contains($proprio)) {
            $this->proprio->add($proprio);
            $proprio->setUtilisateurs($this);
        }

        return $this;
    }

    public function removeProprio(Proprietaires $proprio): self
    {
        if ($this->proprio->removeElement($proprio)) {
            // set the owning side to null (unless already changed)
            if ($proprio->getUtilisateurs() === $this) {
                $proprio->setUtilisateurs(null);
            }
        }

        return $this;
    }
}
