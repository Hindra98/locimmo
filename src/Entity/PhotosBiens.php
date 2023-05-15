<?php

namespace App\Entity;

use App\Repository\PhotosBiensRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotosBiensRepository::class)]
class PhotosBiens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $create_at = null;

    #[ORM\ManyToOne(inversedBy: 'image')]
    private ?Biens $biens = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $video = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeImmutable $create_at = null): self
    {
        $this->create_at = new \DateTimeImmutable;

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

    public function getVideo(): ?int
    {
        return $this->video;
    }

    public function setVideo(?int $video): self
    {
        $this->video = $video;

        return $this;
    }
}
