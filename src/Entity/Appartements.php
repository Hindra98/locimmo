<?php

namespace App\Entity;

use App\Repository\AppartementsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppartementsRepository::class)]
class Appartements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $nbrePieces = null;

    #[ORM\Column]
    private ?bool $etage = null;

    #[ORM\Column]
    private ?bool $ascenceur = null;

    #[ORM\Column]
    private ?bool $garage = null;

    #[ORM\ManyToOne(inversedBy: 'appart')]
    private ?Biens $biens = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrePieces(): ?int
    {
        return $this->nbrePieces;
    }

    public function setNbrePieces(?int $nbrePieces): self
    {
        $this->nbrePieces = $nbrePieces;

        return $this;
    }

    public function isEtage(): ?bool
    {
        return $this->etage;
    }

    public function setEtage(bool $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function isAscenceur(): ?bool
    {
        return $this->ascenceur;
    }

    public function setAscenceur(bool $ascenceur): self
    {
        $this->ascenceur = $ascenceur;

        return $this;
    }

    public function isGarage(): ?bool
    {
        return $this->garage;
    }

    public function setGarage(bool $garage): self
    {
        $this->garage = $garage;

        return $this;
    }

    public function getBiens(): ?Biens
    {
        return $this->biens;
    }

    public function setBiens(?Biens $biens): self
    {
        $this->biens = $biens;

        return $this;
    }
}
