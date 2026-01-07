<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PokemonCaptureHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonCaptureHistoryRepository::class)]
#[ApiResource]
class PokemonCaptureHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ball $ball = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?HuntMethod $huntMethod = null;

    #[ORM\Column]
    private ?bool $isShiny = null;

    #[ORM\Column(nullable: true)]
    private ?int $encounterCount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): static
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getBall(): ?Ball
    {
        return $this->ball;
    }

    public function setBall(?Ball $ball): static
    {
        $this->ball = $ball;

        return $this;
    }

    public function getHuntMethod(): ?HuntMethod
    {
        return $this->huntMethod;
    }

    public function setHuntMethod(?HuntMethod $huntMethod): static
    {
        $this->huntMethod = $huntMethod;

        return $this;
    }

    public function isShiny(): ?bool
    {
        return $this->isShiny;
    }

    public function setIsShiny(bool $isShiny): static
    {
        $this->isShiny = $isShiny;

        return $this;
    }

    public function getEncounterCount(): ?int
    {
        return $this->encounterCount;
    }

    public function setEncounterCount(?int $encounterCount): static
    {
        $this->encounterCount = $encounterCount;

        return $this;
    }
}
