<?php

namespace App\Entity;

use App\Repository\ChambresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChambresRepository::class)]
class Chambres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'chambres')]
    private ?Biens $biens = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
