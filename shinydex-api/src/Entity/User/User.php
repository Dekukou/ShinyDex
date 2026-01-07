<?php

namespace App\Entity\User;

use ApiPlatform\Metadata as Api;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use App\Entity\Capture\PokemonCaptureHistory;
use App\Entity\Capture\HuntSession;

#[Api\ApiResource]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Identifiant de connexion
     */
    #[ORM\Column(length: 50, unique: true)]
    private string $username;

    /**
     * Mot de passe hashé
     */
    #[ORM\Column]
    private string $password;

    /**
     * Rôles Symfony
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Historique de captures du joueur
     */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: PokemonCaptureHistory::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $captures;

    /**
     * Sessions de shiny hunt
     */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: HuntSession::class,
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $huntSessions;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
        $this->huntSessions = new ArrayCollection();
    }

    // ==========================
    // Identité / Sécurité
    // ==========================

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Identifiant Symfony (username)
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * BC Symfony < 6
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

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
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Pas de données sensibles temporaires
    }

    // ==========================
    // Relations métier
    // ==========================

    /** @return Collection<int, PokemonCaptureHistory> */
    public function getCaptures(): Collection
    {
        return $this->captures;
    }

    public function addCapture(PokemonCaptureHistory $capture): self
    {
        if (!$this->captures->contains($capture)) {
            $this->captures->add($capture);
            $capture->setUser($this);
        }

        return $this;
    }

    public function removeCapture(PokemonCaptureHistory $capture): self
    {
        if ($this->captures->removeElement($capture)) {
            if ($capture->getUser() === $this) {
                $capture->setUser(null);
            }
        }

        return $this;
    }

    /** @return Collection<int, HuntSession> */
    public function getHuntSessions(): Collection
    {
        return $this->huntSessions;
    }

    public function addHuntSession(HuntSession $huntSession): self
    {
        if (!$this->huntSessions->contains($huntSession)) {
            $this->huntSessions->add($huntSession);
            $huntSession->setUser($this);
        }

        return $this;
    }

    public function removeHuntSession(HuntSession $huntSession): self
    {
        if ($this->huntSessions->removeElement($huntSession)) {
            if ($huntSession->getUser() === $this) {
                $huntSession->setUser(null);
            }
        }

        return $this;
    }
}
