<?php

namespace App\Entity;

use App\Repository\MaisonsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaisonsRepository::class)]
class Maisons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $nbrePieces = null;

    #[ORM\Column]
    private ?bool $grenier = null;

    #[ORM\ManyToOne(inversedBy: 'maisons')]
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

    public function isGrenier(): ?bool
    {
        return $this->grenier;
    }

    public function setGrenier(bool $grenier): self
    {
        $this->grenier = $grenier;

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
