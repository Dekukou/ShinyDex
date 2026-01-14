<?php

namespace App\Entity\Capture;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User\User;
use App\Entity\Pokedex\Pokemon;
use App\Entity\Pokedex\Game;
use App\Entity\Capture\HuntMethod;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use App\Dto\Hunt\HuntReadDto;
use App\Dto\Hunt\HuntCreateDto;
use App\Dto\Hunt\HuntUpdateDto;
use App\State\Hunt\HuntCollectionProvider;
use App\State\Hunt\HuntCreateProcessor;
use App\State\Hunt\HuntUpdateProcessor;

#[ApiResource(
    operations: [
        new GetCollection(
            provider: HuntCollectionProvider::class
        ),
        new Get(), // IMPORTANT
        new Post(
            input: HuntCreateDto::class,
            output: HuntReadDto::class,
            processor: HuntCreateProcessor::class,
            security: 'is_granted("ROLE_USER")'
        ),
        new Patch(
            input: HuntUpdateDto::class,
            output: HuntReadDto::class,
            processor: HuntUpdateProcessor::class,
            security: 'object.getUser() == user'
        )
    ]
)]
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
    private int $counter = 0;

    #[ORM\Column]
    private bool $isShinyFound = false;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $startedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $endedAt = null;

    /* ===================== */
    /* Getters / Setters     */
    /* ===================== */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;
        return $this;
    }

    public function getMethod(): ?HuntMethod
    {
        return $this->huntMethod;
    }

    public function setMethod(HuntMethod $huntMethod): self
    {
        $this->huntMethod = $huntMethod;
        return $this;
    }

    public function getCounter(): int
    {
        return $this->counter;
    }

    public function setCounter(int $counter): self
    {
        $this->counter = $counter;
        return $this;
    }

    public function isShinyFound(): bool
    {
        return $this->isShinyFound;
    }

    public function setIsShinyFound(bool $isShinyFound): self
    {
        $this->isShinyFound = $isShinyFound;
        return $this;
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): self
    {
        $this->startedAt = $startedAt;
        return $this;
    }

    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeImmutable $endedAt): self
    {
        $this->endedAt = $endedAt;
        return $this;
    }
}
