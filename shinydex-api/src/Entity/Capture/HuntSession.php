<?php

namespace App\Entity\Capture;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User\User;
use App\Entity\Pokedex\Pokemon;
use App\Entity\Pokedex\Game;

#[Api\ApiResource]
#[ORM\Entity]
class HuntSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'huntSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'huntSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HuntMethod $huntMethod = null;

    #[ORM\Column]
    private int $encounters = 0;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $startedAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $endedAt = null;

    public function __construct()
    {
        $this->startedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;
        return $this;
    }

    public function getHuntMethod(): ?HuntMethod
    {
        return $this->huntMethod;
    }

    public function setHuntMethod(?HuntMethod $huntMethod): self
    {
        $this->huntMethod = $huntMethod;
        return $this;
    }

    public function getEncounters(): int
    {
        return $this->encounters;
    }

    public function setEncounters(int $encounters): self
    {
        $this->encounters = $encounters;
        return $this;
    }
}
