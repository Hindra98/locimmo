<?php

namespace App\Entity;

use App\Repository\AbonnementsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbonnementsRepository::class)]
class Abonnements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $create_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expire_at = null;

    #[ORM\ManyToOne(inversedBy: 'abonnement')]
    private ?Proprietaires $proprietaires = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->expire_at;
    }

    public function setExpireAt(\DateTimeInterface $expire_at): self
    {
        $this->expire_at = $expire_at;
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
}
