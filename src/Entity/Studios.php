<?php

namespace App\Entity;

use App\Repository\StudiosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudiosRepository::class)]
class Studios
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'studios')]
    private ?Biens $biens = null;

    public function getId(): ?int
    {
        return $this->id;
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
