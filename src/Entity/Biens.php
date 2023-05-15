<?php

namespace App\Entity;

use App\Repository\BiensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BiensRepository::class)]
class Biens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $prix = null;

    #[ORM\Column]
    private ?float $superficie = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $etat = null;

    #[ORM\OneToMany(mappedBy: 'biens', targetEntity: Favoris::class, orphanRemoval: true)]
    private Collection $favoris;

    #[ORM\OneToMany(mappedBy: 'biens', targetEntity: PhotosBiens::class)]
    private Collection $image;

    #[ORM\ManyToOne(inversedBy: 'bien')]
    private ?Proprietaires $proprietaires = null;

    #[ORM\OneToMany(mappedBy: 'biens', targetEntity: Studios::class)]
    private Collection $studios;

    #[ORM\OneToMany(mappedBy: 'biens', targetEntity: Chambres::class)]
    private Collection $chambres;

    #[ORM\OneToMany(mappedBy: 'biens', targetEntity: Maisons::class)]
    private Collection $maisons;

    #[ORM\OneToMany(mappedBy: 'biens', targetEntity: Appartements::class)]
    private Collection $appart;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->studios = new ArrayCollection();
        $this->chambres = new ArrayCollection();
        $this->maisons = new ArrayCollection();
        $this->appart = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(float $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

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

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setBiens($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getBiens() === $this) {
                $favori->setBiens(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PhotosBiens>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(PhotosBiens $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setBiens($this);
        }

        return $this;
    }

    public function removeImage(PhotosBiens $image): self
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBiens() === $this) {
                $image->setBiens(null);
            }
        }

        return $this;
    }

    public function getProprietaires(): ?Proprietaires
    {
        return $this->proprietaires;
    }

    public function setProprietaires(?Proprietaires $proprietaires): self
    {
        $this->proprietaires = $proprietaires;

        return $this;
    }

    /**
     * @return Collection<int, Studios>
     */
    public function getStudios(): Collection
    {
        return $this->studios;
    }

    public function addStudio(Studios $studio): self
    {
        if (!$this->studios->contains($studio)) {
            $this->studios->add($studio);
            $studio->setBiens($this);
        }

        return $this;
    }

    public function removeStudio(Studios $studio): self
    {
        if ($this->studios->removeElement($studio)) {
            // set the owning side to null (unless already changed)
            if ($studio->getBiens() === $this) {
                $studio->setBiens(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chambres>
     */
    public function getChambres(): Collection
    {
        return $this->chambres;
    }

    public function addChambre(Chambres $chambre): self
    {
        if (!$this->chambres->contains($chambre)) {
            $this->chambres->add($chambre);
            $chambre->setBiens($this);
        }

        return $this;
    }

    public function removeChambre(Chambres $chambre): self
    {
        if ($this->chambres->removeElement($chambre)) {
            // set the owning side to null (unless already changed)
            if ($chambre->getBiens() === $this) {
                $chambre->setBiens(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Maisons>
     */
    public function getMaisons(): Collection
    {
        return $this->maisons;
    }

    public function addMaison(Maisons $maison): self
    {
        if (!$this->maisons->contains($maison)) {
            $this->maisons->add($maison);
            $maison->setBiens($this);
        }

        return $this;
    }

    public function removeMaison(Maisons $maison): self
    {
        if ($this->maisons->removeElement($maison)) {
            // set the owning side to null (unless already changed)
            if ($maison->getBiens() === $this) {
                $maison->setBiens(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Appartements>
     */
    public function getAppart(): Collection
    {
        return $this->appart;
    }

    public function addAppart(Appartements $appart): self
    {
        if (!$this->appart->contains($appart)) {
            $this->appart->add($appart);
            $appart->setBiens($this);
        }

        return $this;
    }

    public function removeAppart(Appartements $appart): self
    {
        if ($this->appart->removeElement($appart)) {
            // set the owning side to null (unless already changed)
            if ($appart->getBiens() === $this) {
                $appart->setBiens(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
