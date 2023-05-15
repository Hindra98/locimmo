<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $create_at = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profil = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $sexe = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Favoris::class)]
    private Collection $favoris;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Commentaires::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'utilisateur_send', targetEntity: Messages::class)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'utilisateur_receive', targetEntity: Messages::class)]
    private Collection $message;

    #[ORM\Column]
    private ?\DateTimeImmutable $update_at = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Administrateur::class)]
    private Collection $admin;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Proprietaires::class)]
    private Collection $proprio;

    public function __construct()
    {
        $this->favoris = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->message = new ArrayCollection();
        $this->admin = new ArrayCollection();
        $this->proprio = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProfil(): ?string
    {
        return $this->profil;
    }

    public function setProfil(?string $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

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
            $favori->setUtilisateur($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getUtilisateur() === $this) {
                $favori->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUtilisateur() === $this) {
                $commentaire->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUtilisateurSend($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUtilisateurSend() === $this) {
                $message->setUtilisateurSend(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->update_at;
    }

    public function setUpdateAt(\DateTimeImmutable $update_at = null): self
    {
        $this->update_at = new \DateTimeImmutable;

        return $this;
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
            $admin->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdmin(Administrateur $admin): self
    {
        if ($this->admin->removeElement($admin)) {
            // set the owning side to null (unless already changed)
            if ($admin->getUtilisateur() === $this) {
                $admin->setUtilisateur(null);
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
            $proprio->setUtilisateur($this);
        }

        return $this;
    }

    public function removeProprio(Proprietaires $proprio): self
    {
        if ($this->proprio->removeElement($proprio)) {
            // set the owning side to null (unless already changed)
            if ($proprio->getUtilisateur() === $this) {
                $proprio->setUtilisateur(null);
            }
        }

        return $this;
    }
}
