<?php

namespace App\Service;


use App\Entity\Proprietaires;
use App\Entity\Utilisateur;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class Helpers
{
    public function __construct(private Security $security) {}
    public function getUser(): Utilisateur | null
    {
        return $this->security->getUser();
    }
}